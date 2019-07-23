<?php

/**
 * Copyright © Ergonaut Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Account\Domain\Event\UserCreatedEvent;
use Ergonode\Account\Domain\Repository\RoleRepositoryInterface;
use Ergonode\Account\Domain\ValueObject\Privilege;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use JMS\Serializer\SerializerInterface;
use Webmozart\Assert\Assert;

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
     * @var RoleRepositoryInterface
     */
    private $repository;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param Connection              $connection
     * @param RoleRepositoryInterface $repository
     * @param SerializerInterface     $serializer
     */
    public function __construct(Connection $connection, RoleRepositoryInterface $repository, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->repository = $repository;
        $this->serializer = $serializer;
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
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Throwable
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$event instanceof UserCreatedEvent) {
            throw new UnsupportedEventException($event, UserCreatedEvent::class);
        }

        $role = $this->repository->load($event->getRoleId());

        Assert::notNull($role);
        /** @var Privilege[] $privileges */
        $privileges = $role->getPrivileges();

        $this->connection->beginTransaction();
        try {
            $this->connection->insert(
                self::TABLE,
                [
                    'id' => $event->getId()->getValue(),
                    'first_name' => $event->getFirstName(),
                    'last_name' => $event->getLastName(),
                    'username' => $event->getEmail(),
                    'role_id' => $event->getRoleId()->getValue(),
                    'roles' => $this->serializer->serialize($privileges, 'json'),
                    'language' => $event->getLanguage()->getCode(),
                    'password' => $event->getPassword()->getValue(),
                ]
            );
            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();
            throw $exception;
        }
    }
}
