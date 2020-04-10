<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Persistence\Dbal\Projector\User;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Account\Domain\Event\User\UserLanguagePrivilegesChangedEvent;
use JMS\Serializer\SerializerInterface;

/**
 */
class UserLanguagePrivilegesChangedEventProjector
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
     * @param UserLanguagePrivilegesChangedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(UserLanguagePrivilegesChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'language_privileges' => $this->serializer->serialize($event->getTo(), 'json'),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
