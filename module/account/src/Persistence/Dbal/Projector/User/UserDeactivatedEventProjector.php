<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Persistence\Dbal\Projector\User;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Account\Domain\Event\User\UserDeactivatedEvent;

/**
 */
class UserDeactivatedEventProjector
{
    private const TABLE = 'users';

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
     * @param UserDeactivatedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(UserDeactivatedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'is_active' => false,
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
