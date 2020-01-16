<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Persistence\Dbal\Projector\Role;

use Doctrine\DBAL\Connection;
use Ergonode\Account\Domain\Event\Role\RoleCreatedEvent;
use JMS\Serializer\SerializerInterface;

/**
 */
class RoleCreatedEventProjector
{
    private const TABLE = 'roles';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param Connection          $connection
     * @param SerializerInterface $serializer
     */
    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * @param RoleCreatedEvent $event
     *
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
                'privileges' => $this->serializer->serialize($event->getPrivileges(), 'json'),
                'hidden' => $event->isHidden(),
            ],
            [
                'hidden' => \PDO::PARAM_BOOL,
            ]
        );
    }
}
