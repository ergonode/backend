<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Product\Domain\Entity\ProductId;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Workflow\Domain\Entity\StatusId;
use Ramsey\Uuid\Uuid;

/**
 */
class DbalProductQuery implements ProductQueryInterface
{
    private const PRODUCT_TABLE = 'public.product';
    private const TEMPLATE_TABLE = 'designer.template';
    private const VALUE_TABLE = 'public.product_value';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritDoc}
     */
    public function findBySku(Sku $sku): ?array
    {
        $qb = $this->getQuery();
        $result = $qb->where($qb->expr()->eq('sku', ':sku'))
            ->setParameter(':sku', $sku->getValue())
            ->execute()
            ->fetch();
        if (false !== $result) {
            return $result;
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getAllIds(): array
    {
        return $this->connection->createQueryBuilder()
            ->select('id')
            ->from('product')
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * {@inheritDoc}
     */
    public function findProductIdByCategoryId(CategoryId $categoryId): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->select('p.id')
            ->from('public.product', 'p')
            ->join('p', 'public.product_category_product', 'pcp', 'p.id = pcp.product_id')
            ->where($queryBuilder->expr()->in('pcp.category_id', ':category'))
            ->setParameter(':category', $categoryId->getValue());
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
     * {@inheritDoc}
     */
    public function findProductIdByTemplateId(TemplateId $templateId): array
    {
        $queryBuilder = $this->getQuery();
        $queryBuilder
            ->select('p.id')
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
     * {@inheritDoc}
     */
    public function findProductIdByAttributeId(AttributeId $attributeId, ?Uuid $valueId = null): array
    {
        $queryBuilder = $this->connection->createQueryBuilder()
            ->select('product_id')
            ->from(self::VALUE_TABLE)
            ->where('attribute_id = :attribute')
            ->setParameter('attribute', $attributeId->getValue());
        if ($valueId) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('value_id', ':valueId'));
            $queryBuilder->setParameter('valueId', $valueId->toString());
        }

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
     * {@inheritDoc}
     */
    public function findProductIdByStatusId(StatusId $statusId): array
    {
        $statusCode = $this->connection->createQueryBuilder()
            ->select('s.code')
            ->from('public.status', 's')
            ->where('s.id = :id')
            ->setParameter('id', $statusId->getValue())
            ->setMaxResults(1)
            ->execute()->fetchColumn();

        // @todo This is unacceptable!
        $attributeId = $this->connection->createQueryBuilder()
            ->select('a.id')
            ->from('public.attribute', 'a')
            ->where('a.code = \'esa_status\'')
            ->setMaxResults(1)
            ->execute()->fetchColumn();

        // @todo That's too!
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->select('p.id')
            ->from(self::PRODUCT_TABLE, 'p')
            ->join('p', 'designer.draft', 'd', 'p.id = d.product_id')
            ->join('d', 'designer.draft_value', 'dv', 'd.id = dv.draft_id')
            ->where('dv.value = :statusCode')
            ->andWhere('dv.element_id = :attributeId')
            ->setParameter('statusCode', $statusCode)
            ->setParameter('attributeId', $attributeId);

        return $queryBuilder->execute()->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('p.id, p.index, p.sku, p.version, t.name AS template')
            ->from(self::PRODUCT_TABLE, 'p')
            ->join('p', self::TEMPLATE_TABLE, 't', 't.id = p.template_id');
    }
}
