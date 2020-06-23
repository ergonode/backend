<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Multimedia\Domain\Event\AvatarCreatedEvent;

/**
 */
class AvatarCreatedEventProjector
{
    private const TABLE = 'avatar';

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
     * @param AvatarCreatedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(AvatarCreatedEvent $event): void
    {
        /**
         * @var $event AvatarCreatedEvent
         */
        $this->connection->insert(
            self::TABLE,
            [
                'id' => $event->getAggregateId(),
                'extension' => $event->getExtension(),
                'size' => $event->getSize(),
                'mime' => $event->getMime(),
                'hash' => $event->getHash(),
            ]
        );
    }
}
