<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Infrastructure\Persistence\Projector\Segment;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Segment\Domain\Event\SegmentStatusChangedEvent;

class DbalSegmentStatusChangedEventProjector
{
    private const TABLE = 'segment';

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
     * @param SegmentStatusChangedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(SegmentStatusChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'status' => (string) $event->getTo(),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
