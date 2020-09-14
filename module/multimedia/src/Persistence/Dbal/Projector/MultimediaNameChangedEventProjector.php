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
use Ergonode\Multimedia\Domain\Event\MultimediaNameChangedEvent;

/**
 */
class MultimediaNameChangedEventProjector
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
     * @param MultimediaNameChangedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(MultimediaNameChangedEvent $event): void
    {
        /**
         * @var $event MultimediaCreatedEvent
         */
        $this->connection->update(
            self::TABLE,
            [
                'name' => $event->getName(),
                'updated_at' => (new \DateTime())->format('Y-m-d H:i:s'),
            ],
            [
                'id' => $event->getAggregateId(),
            ]
        );
    }
}
