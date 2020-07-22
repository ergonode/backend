<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Channel\Domain\Event\ChannelCreatedEvent;

/**
 */
class ChannelCreatedEventProjector
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
     * @param ChannelCreatedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(ChannelCreatedEvent $event): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'id' => $event->getAggregateId()->getValue(),
                'name' => $event->getName(),
                'type' => $event->getType(),
            ]
        );
    }
}
