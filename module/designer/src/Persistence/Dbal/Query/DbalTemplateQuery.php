<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

/**
 */
class DbalTemplateQuery implements TemplateQueryInterface
{
    private const TABLE = 'designer.template';
    private const PRODUCT_TABLE = 'designer.product';
    private const FIELDS = [
        't.id',
        't.name',
        't.default_image',
        't.default_text',
        't.image_id',
        't.template_group_id AS group_id',
    ];

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return DataSetInterface
     */
    public function getDataSet(): DataSetInterface
    {
        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $this->getQuery()->getSQL()), 't');

        return new DbalDataSet($result);
    }

    /**
     * @param Language $language
     *
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
     * {@inheritDoc}
     */
    public function findTemplateIdByAttributeId(AttributeId $attributeId): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->select('template_id')
            ->from('designer.template_element')
            ->where('properties ->> \'variant\' = \'attribute\'')
            ->andWhere('properties ->> \'attribute_id\' = :attribute')
            ->setParameter('attribute', $attributeId->getValue());

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
            ->select('p.product_id')
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

    /**
     * @param ProductId $productId
     *
     * @return TemplateId
     */
    public function findProductTemplateId(ProductId $productId): TemplateId
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $result = $queryBuilder
            ->select('p.template_id')
            ->from(self::PRODUCT_TABLE, 'p')
            ->where($queryBuilder->expr()->eq('p.product_id', ':productId'))
            ->setParameter(':productId', $productId->getValue())
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        return new TemplateId($result);
    }

    /**
     * @param string $code
     *
     * @return TemplateId|null
     */
    public function findTemplateIdByCode(string $code): ?TemplateId
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $result = $queryBuilder
            ->select('p.template_id')
            ->from(self::TABLE, 'p')
            ->where($queryBuilder->expr()->eq('p.name', ':name'))
            ->setParameter(':name', $code)
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        if ($result) {
            return new TemplateId($result);
        }

        return null;
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from(self::TABLE, 't');
    }
}
