<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Persistence\Dbal\Projector\Segment;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Segment\Domain\Event\SegmentDeletedEvent;

/**
 */
class SegmentDeletedEventProjector
{
    private const TABLE = 'segment';
    private const TABLE_PRODUCT = 'segment_product';

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
     * @param SegmentDeletedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(SegmentDeletedEvent $event): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );

        $this->connection->delete(
            self::TABLE_PRODUCT,
            [
                'segment_id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
