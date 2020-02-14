<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ramsey\Uuid\Uuid;

/**
 */
class DbalProductQuery implements ProductQueryInterface
{
    private const PRODUCT_TABLE = 'public.product';
    private const VALUE_TABLE = 'public.product_value';
    private const SEGMENT_PRODUCT_TABLE = 'public.segment_product';

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
        $result = $this->connection->createQueryBuilder()
            ->select('id')
            ->from(self::PRODUCT_TABLE)
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        if (false !== $result) {
            return $result;
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getAllSkus(): array
    {
        $result = $this->connection->createQueryBuilder()
            ->select('sku')
            ->from(self::PRODUCT_TABLE)
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        if (false !== $result) {
            return $result;
        }

        return null;
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
     * @param array $skus
     *
     * @return array
     */
    public function findProductIdsBySkus(array $skus = []): array
    {
        $qb = $this->connection->createQueryBuilder()
            ->select('id')
            ->from(self::PRODUCT_TABLE);

        if ($skus) {
            $qb->andWhere($qb->expr()->in('sku', ':skus'))
                ->setParameter(':skus', $skus, \Doctrine\DBAL\Connection::PARAM_STR_ARRAY);
        }

        $result = $qb->execute()->fetchAll(\PDO::FETCH_COLUMN);
        if (false === $result) {
            $result = [];
        }

        foreach ($result as &$item) {
            $item = new ProductId($item);
        }

        return $result;
    }

    /**
     * @param array $segmentIds
     *
     * @return array
     */
    public function findProductIdsBySegments(array $segmentIds): array
    {
        $qb = $this->connection->createQueryBuilder()
            ->select('product_id')
            ->from(self::SEGMENT_PRODUCT_TABLE);

        if ($segmentIds) {
            $qb->andWhere($qb->expr()->in('segment_id', ':segmentIds'))
                ->setParameter(':segmentIds', $segmentIds, \Doctrine\DBAL\Connection::PARAM_STR_ARRAY);
        }

        $result = $qb->execute()->fetchAll(\PDO::FETCH_COLUMN);

        if (false === $result) {
            $result = [];
        }

        foreach ($result as &$item) {
            $item = new ProductId($item);
        }

        return $result;
    }


    /**
     * @return array
     */
    public function getDictionary(): array
    {
        $query = $this->getQuery();

        return $query
            ->select('id', 'sku')
            ->execute()
            ->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('p.id, p.index, p.sku, p.version')
            ->from(self::PRODUCT_TABLE, 'p');
    }
}
