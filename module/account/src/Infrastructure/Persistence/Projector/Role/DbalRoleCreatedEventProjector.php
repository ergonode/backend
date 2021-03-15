<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Persistence\Projector\Role;

use Doctrine\DBAL\Connection;
use Ergonode\Account\Domain\Event\Role\RoleCreatedEvent;
use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;

class DbalRoleCreatedEventProjector
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
     * @throws \Doctrine\DBAL\DBALException
     */
    public function __invoke(RoleCreatedEvent $event): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'id' => $event->getAggregateId()->getValue(),
                'name' => $event->getName(),
                'description' => $event->getDescription(),
                'privileges' => $this->serializer->serialize($event->getPrivileges()),
                'hidden' => $event->isHidden(),
            ],
            [
                'hidden' => \PDO::PARAM_BOOL,
            ]
        );
    }
}
