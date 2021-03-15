<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Ergonode\Segment\Domain\Query\SegmentProductsGridQueryInterface;

class DbalSegmentProductsGridQuery implements SegmentProductsGridQueryInterface
{
    private const PRODUCT_TABLE = 'public.product';
    private const SEGMENT_PRODUCT_TABLE = 'public.segment_product';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getGridQuery(SegmentId $segmentId): QueryBuilder
    {
        $query = $this->connection->createQueryBuilder();

        return $query
            ->select('sp.segment_id, sp.product_id')
            ->from(self::SEGMENT_PRODUCT_TABLE, 'sp')
            ->join('sp', self::PRODUCT_TABLE, 'p', 'p.id = sp.product_id')
            ->select('p.id', 'p.sku')
            ->where($query->expr()->eq('sp.segment_id', ':segmentId'))
            ->andWhere('sp.available = true')
            ->setParameter(':segmentId', $segmentId->getValue());
    }
}
