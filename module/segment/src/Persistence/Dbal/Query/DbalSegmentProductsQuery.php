<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Grid\DbalDataSet;
use Ergonode\Segment\Domain\Query\SegmentProductsQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;

/**
 */
class DbalSegmentProductsQuery implements SegmentProductsQueryInterface
{
    private const PRODUCT_TABLE = 'public.product';

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
     * {@inheritDoc}
     */
    public function getDataSet(SegmentId $segmentId): DbalDataSet
    {
        $query = $this->getQuery();
        $query->leftJoin('p', 'public.segment_product', 'sp', 'p.id = sp.product_id')
            ->where($query->expr()->eq('sp.segment_id', ':segmentId'))
            ->andWhere('sp.available = true');
        $result = $this->connection->createQueryBuilder();
        $result->setParameter(':segmentId', $segmentId->getValue());
        $result->select('*');
        $result->from(sprintf('(%s)', $query->getSQL()), 't');

        return new DbalDataSet($result);
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('p.id, p.sku')
            ->from(self::PRODUCT_TABLE, 'p');
    }
}
