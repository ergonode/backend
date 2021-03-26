<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;

abstract class AbstractDbalSegmentUpdateEventProjector
{
    private const TABLE_PRODUCT = 'segment_product';

    protected Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function update(SegmentId $id): void
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->update(self::TABLE_PRODUCT)
            ->set('calculated_at', ':calculatedAt')
            ->where($qb->expr()->eq('segment_id', ':segmentId'))
            ->andWhere($qb->expr()->isNotNull('calculated_at'))
            ->setParameter(':segmentId', $id->getValue())
            ->setParameter(':calculatedAt', null)
            ->execute();
    }
}
