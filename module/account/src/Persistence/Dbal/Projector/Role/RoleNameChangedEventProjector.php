<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Persistence\Dbal\Projector\Role;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Account\Domain\Event\Role\RoleNameChangedEvent;

/**
 */
class RoleNameChangedEventProjector
{
    private const TABLE = 'roles';

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
     * @param RoleNameChangedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(RoleNameChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'name' => $event->getTo(),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
