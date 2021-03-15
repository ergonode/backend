<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Types\Types;
use Ergonode\Multimedia\Domain\Event\MultimediaCreatedEvent;

class DbalMultimediaCreatedEventProjector
{
    private const TABLE = 'multimedia';

    protected Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
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
                'created_at' => new \DateTime(),
            ],
            [
                'created_at' => Types::DATETIMETZ_MUTABLE,
            ],
        );
    }
}
