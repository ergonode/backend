<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Segment\Domain\Event\SegmentConditionSetChangedEvent;

/**
 */
class SegmentConditionSetChangedEventProjector
{
    private const TABLE = 'segment';

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
     * @param SegmentConditionSetChangedEvent $event
     *
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
