<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Persistence\Projector\User;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Account\Domain\Event\User\UserActivatedEvent;

class DbalUserActivatedEventProjector
{
    private const TABLE = 'users';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws DBALException
     */
    public function __invoke(UserActivatedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'is_active' => true,
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ],
            [
                'is_active' => \PDO::PARAM_BOOL,
            ]
        );
    }
}
