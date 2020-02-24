<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Persistence\Dbal\Projector\User;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Account\Domain\Event\User\UserLastNameChangedEvent;

/**
 */
class UserLastNameChangedEventProjector
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
     * @param UserLastNameChangedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(UserLastNameChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'last_name' => $event->getTo(),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
