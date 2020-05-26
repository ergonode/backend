<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Persistence\Dbal\Projector\User;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Account\Domain\Event\User\UserCreatedEvent;
use JMS\Serializer\SerializerInterface;

/**
 */
class UserCreatedEventProjector
{
    private const TABLE = 'users';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

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
     * @param UserCreatedEvent $event
     *
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
                    $this->serializer->serialize($event->getLanguagePrivilegesCollection(), 'json'),
                'language' => $event->getLanguage()->getCode(),
                'password' => $event->getPassword()->getValue(),
                'is_active' => $event->isActive(),
                'avatar_id' => $event->getAvatarId() ? $event->getAvatarId()->getValue() : null,
            ],
            [
                'is_active' => \PDO::PARAM_BOOL,
            ]
        );
    }
}
