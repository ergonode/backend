<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\Factory\DbalDataSetFactory;
use Ergonode\Segment\Domain\Query\SegmentProductsQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;

class DbalSegmentProductsQuery implements SegmentProductsQueryInterface
{
    private const PRODUCT_TABLE = 'public.product';
    private const SEGMENT_PRODUCT_TABLE = 'public.segment_product';

    private Connection $connection;

    private DbalDataSetFactory $dataSetFactory;

    public function __construct(Connection $connection, DbalDataSetFactory $dataSetFactory)
    {
        $this->connection = $connection;
        $this->dataSetFactory = $dataSetFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function getDataSet(SegmentId $segmentId): DataSetInterface
    {
        $query = $this->getQuery();

        $query->join('sp', self::PRODUCT_TABLE, 'p', 'p.id = sp.product_id')
            ->select('p.id', 'p.sku')
            ->where($query->expr()->eq('sp.segment_id', ':segmentId'))
            ->andWhere('sp.available = true');

        $result = $this->connection->createQueryBuilder();
        $result->setParameter(':segmentId', $segmentId->getValue());
        $result->select('*');
        $result->from(sprintf('(%s)', $query->getSQL()), 't');

        return $this->dataSetFactory->create($result);
    }

    /**
     * @return string[]
     */
    public function getProducts(SegmentId $segmentId): array
    {
        $qb = $this->getQuery();

        return $qb->select('sp.product_id')
            ->where($qb->expr()->eq('segment_id', ':segmentId'))
            ->andWhere($qb->expr()->eq('available', ':available'))
            ->setParameter(':available', true)
            ->setParameter(':segmentId', $segmentId->getValue())
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * @return string[]
     */
    public function getProductsByType(SegmentId $segmentId, string $type): array
    {
        $qb = $this->getQuery();

        return $qb->select('sp.product_id')
            ->join('sp', self::PRODUCT_TABLE, 'p', 'p.id = sp.product_id')
            ->where($qb->expr()->eq('sp.segment_id', ':segmentId'))
            ->andWhere($qb->expr()->eq('sp.available', ':available'))
            ->andWhere($qb->expr()->eq('p.type', ':type'))
            ->setParameter(':available', true)
            ->setParameter(':segmentId', $segmentId->getValue())
            ->setParameter(':type', $type)
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('sp.segment_id, sp.product_id')
            ->from(self::SEGMENT_PRODUCT_TABLE, 'sp');
    }
}
