<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Persistence\Projector\Segment;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Segment\Domain\Event\SegmentConditionSetChangedEvent;

class DbalSegmentConditionSetChangedEventProjector
{
    private const TABLE = 'segment';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws DBALException
     */
    public function __invoke(SegmentConditionSetChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'condition_set_id' => $event->getTo() ? $event->getTo()->getValue() : null,
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
