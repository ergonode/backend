<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Condition\Domain\Event\ConditionSetDeletedEvent;

class DbalConditionSetDeletedEventProjector
{
    private const TABLE = 'condition_set';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws DBALException
     */
    public function __invoke(ConditionSetDeletedEvent $event): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
