<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Persistence\Dbal\Projector\User;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Account\Domain\Event\User\UserFirstNameChangedEvent;

/**
 */
class UserFirstNameChangedEventProjector
{
    private const TABLE = 'users';

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
     * @param UserFirstNameChangedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(UserFirstNameChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'first_name' => $event->getTo(),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
