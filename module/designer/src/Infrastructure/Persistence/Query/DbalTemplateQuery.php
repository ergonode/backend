<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

class DbalTemplateQuery implements TemplateQueryInterface
{
    private const TEMPLATE_TABLE = 'designer.template';
    private const PRODUCT_TABLE = 'product';
    private const FIELDS = [
        't.id',
        't.name',
        't.default_image',
        't.default_label',
        't.image_id',
        't.template_group_id AS group_id',
    ];

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return array
     */
    public function getDictionary(Language $language): array
    {
        return $this->getQuery()
            ->select('id, name')
            ->orderBy('name', 'desc')
            ->execute()
            ->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->getQuery()
            ->select('id')
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * {@inheritDoc}
     */
    public function findTemplateIdByAttributeId(AttributeId $attributeId): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->select('template_id')
            ->from('designer.template_attribute')
            ->where($queryBuilder->expr()->eq('attribute_id', ':attribute'))
            ->setParameter('attribute', $attributeId->getValue())
            ->groupBy('template_id');

        $result = $queryBuilder->execute()->fetchAll(\PDO::FETCH_COLUMN);

        if (false === $result) {
            $result = [];
        }

        foreach ($result as &$item) {
            $item = new TemplateId($item);
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function findProductIdByTemplateId(TemplateId $templateId): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->select('p.id')
            ->from(self::PRODUCT_TABLE, 'p')
            ->where($queryBuilder->expr()->eq('p.template_id', ':templateId'))
            ->setParameter(':templateId', $templateId->getValue());
        $result = $queryBuilder->execute()->fetchAll(\PDO::FETCH_COLUMN);

        if (false === $result) {
            $result = [];
        }

        foreach ($result as &$item) {
            $item = new ProductId($item);
        }

        return $result;
    }

    public function findTemplateIdByCode(string $code): ?TemplateId
    {
        $queryBuilder = $this->getQuery();
        $result = $queryBuilder
            ->select('t.id')
            ->where($queryBuilder->expr()->eq('name', ':name'))
            ->setParameter(':name', $code)
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        if ($result) {
            return new TemplateId($result);
        }

        return null;
    }

    /**
     * @return array
     */
    public function getMultimediaRelation(MultimediaId $id): array
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb->select('id, name')
            ->from('designer.template')
            ->where($qb->expr()->eq('image_id', ':multimediaId'))
            ->setParameter(':multimediaId', $id->getValue())
            ->execute()
            ->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * {@inheritDoc}
     */
    public function findTemplateIdsByProductIds(array $productIds): array
    {
        $qb = $this->connection->createQueryBuilder();
        $productIdsValues = [];
        foreach ($productIds as $productId) {
            $productIdsValues[] = $productId->getValue();
        }
        $result = $qb->select('DISTINCT template_id')
            ->where($qb->expr()->in('id', ':productsIds'))
            ->setParameter(':productsIds', $productIdsValues, Connection::PARAM_STR_ARRAY)
            ->from(self::PRODUCT_TABLE)
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        if (false === $result) {
            $result = [];
        }

        foreach ($result as &$item) {
            $item = new TemplateId($item);
        }

        return $result;
    }

    /**
     * @return array
     */
    public function autocomplete(
        string $search = null,
        int $limit = null,
        string $field = null,
        ?string $order = 'ASC'
    ): array {
        $query = $this->connection->createQueryBuilder()
            ->select('id, name as label')
            ->from(self::TEMPLATE_TABLE);

        if ($search) {
            $query->orWhere('name ILIKE :search');
            $query->setParameter(':search', '%'.$search.'%');
        }

        if ($field) {
            $query->orderBy($field, $order);
        }

        if ($limit) {
            $query->setMaxResults($limit);
        }

        return $query
            ->execute()
            ->fetchAll();
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from(self::TEMPLATE_TABLE, 't');
    }
}
