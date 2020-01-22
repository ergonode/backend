<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Channel\Domain\Event\ChannelNameChangedEvent;

/**
 */
class ChannelNameChangedEventProjector
{
    private const TABLE = 'exporter.channel';

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
     * @param ChannelNameChangedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(ChannelNameChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'name' => $event->getTo(),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );

        $this->connection->commit();
    }
}
