<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Persistence\Dbal\Projector\User;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Account\Domain\Event\User\UserAvatarChangedEvent;

/**
 */
class UserAvatarChangedEventProjector
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
     * @param UserAvatarChangedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(UserAvatarChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'avatar_id' => $event->getAvatarId() ? $event->getAvatarId()->getValue() : null,
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
