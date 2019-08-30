<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Persistence\Dbal\Projector\User;

use Doctrine\DBAL\Connection;
use Ergonode\Account\Domain\Event\User\UserCreatedEvent;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;

/**
 */
class UserCreatedEventProjector implements DomainEventProjectorInterface
{
    private const TABLE = 'users';

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
     * @param DomainEventInterface $event
     *
     * @return bool
     */
    public function support(DomainEventInterface $event): bool
    {
        return $event instanceof UserCreatedEvent;
    }

    /**
     * @param AbstractId           $aggregateId
     * @param DomainEventInterface $event
     *
     * @throws UnsupportedEventException
     * @throws \Throwable
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$event instanceof UserCreatedEvent) {
            throw new UnsupportedEventException($event, UserCreatedEvent::class);
        }

        $this->connection->transactional(function () use ($event) {
            $this->connection->insert(
                self::TABLE,
                [
                    'id' => $event->getId()->getValue(),
                    'first_name' => $event->getFirstName(),
                    'last_name' => $event->getLastName(),
                    'username' => $event->getEmail(),
                    'role_id' => $event->getRoleId()->getValue(),
                    'language' => $event->getLanguage()->getCode(),
                    'password' => $event->getPassword()->getValue(),
                    'is_active' => $event->isActive(),
                ],
                [
                    'is_active' => \PDO::PARAM_BOOL,
                ]
            );
        });
    }
}
