<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Multimedia\Domain\Event\MultimediaCreatedEvent;

/**
 */
class MultimediaCreatedEventProjector
{
    private const TABLE = 'multimedia';

    /**
     * @var Connection
     */
    protected Connection $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param MultimediaCreatedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(MultimediaCreatedEvent $event): void
    {
        /**
         * @var $event MultimediaCreatedEvent
         */
        $this->connection->insert(
            self::TABLE,
            [
                'id' => $event->getAggregateId(),
                'name' => $event->getName(),
                'extension' => $event->getExtension(),
                'size' => $event->getSize(),
                'mime' => $event->getMime(),
                'hash' => $event->getHash(),
            ]
        );
    }
}
