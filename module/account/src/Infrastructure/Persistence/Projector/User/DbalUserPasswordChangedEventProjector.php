<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Infrastructure\Persistence\Projector\User;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Account\Domain\Event\User\UserPasswordChangedEvent;

/**
 */
class DbalUserPasswordChangedEventProjector
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
     * @param UserPasswordChangedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(UserPasswordChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'password' => $event->getPassword(),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
