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
use Ergonode\Multimedia\Domain\Event\MultimediaNameChangedEvent;

class DbalMultimediaNameChangedEventProjector
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
    public function __invoke(MultimediaNameChangedEvent $event): void
    {
        /**
         * @var $event MultimediaCreatedEvent
         */
        $this->connection->update(
            self::TABLE,
            [
                'name' => $event->getName(),
                'updated_at' => new \DateTime(),
            ],
            [
                'id' => $event->getAggregateId(),
            ],
            [
                'updated_at' => Types::DATETIMETZ_MUTABLE,
            ],
        );
    }
}
