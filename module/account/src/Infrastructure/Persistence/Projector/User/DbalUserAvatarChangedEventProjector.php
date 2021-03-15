<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Persistence\Projector\User;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Account\Domain\Event\User\UserAvatarChangedEvent;

class DbalUserAvatarChangedEventProjector
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
    public function __invoke(UserAvatarChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'avatar' => true,
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
