<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Persistence\Projector\User;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Account\Domain\Event\User\UserCreatedEvent;
use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;

class DbalUserCreatedEventProjector
{
    private const TABLE = 'users';

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
    public function __invoke(UserCreatedEvent $event): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'id' => $event->getAggregateId()->getValue(),
                'first_name' => $event->getFirstName(),
                'last_name' => $event->getLastName(),
                'username' => $event->getEmail(),
                'role_id' => $event->getRoleId()->getValue(),
                'language_privileges_collection' =>
                    $this->serializer->serialize($event->getLanguagePrivilegesCollection()),
                'language' => $event->getLanguage()->getCode(),
                'password' => $event->getPassword()->getValue(),
                'is_active' => $event->isActive(),
            ],
            [
                'is_active' => \PDO::PARAM_BOOL,
            ]
        );
    }
}
