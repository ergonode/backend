<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Reader\Domain\Event\ReaderDeletedEvent;

/**
 */
class ReaderDeletedEventProjector
{
    private const TABLE = 'importer.reader';

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
     * @param ReaderDeletedEvent $event
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function __invoke(ReaderDeletedEvent $event): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
