<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Persistence\Projector\Role;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Account\Domain\Event\Role\RolePrivilegesChangedEvent;
use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;

class DbalRolePrivilegesChangedEventProjector
{
    private const TABLE = 'roles';

    private Connection $connection;

    private SerializerInterface $serializer;

    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * @throws DBALException
     */
    public function __invoke(RolePrivilegesChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'privileges' => $this->serializer->serialize($event->getTo()),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
