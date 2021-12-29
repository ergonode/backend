<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Multimedia\Domain\Event\MultimediaDeletedEvent;

class DbalMultimediaDeletedEventProjector
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
    public function __invoke(MultimediaDeletedEvent $event): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'id' => $event->getAggregateId(),
            ]
        );
    }
}
