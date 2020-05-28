<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Channel\Domain\Event\ChannelDeletedEvent;

/**
 */
class ChannelDeletedEventProjector
{
    private const TABLE = 'exporter.channel';
    private const TABLE_EXPORT = 'exporter.export_channel';

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
     * @param ChannelDeletedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(ChannelDeletedEvent $event): void
    {
        $this->connection->delete(
            self::TABLE_EXPORT,
            [
                'channel_id' =>  $event->getAggregateId()->getValue(),
            ]
        );

        $this->connection->delete(
            self::TABLE,
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
