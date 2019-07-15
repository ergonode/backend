<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Account\Domain\Event\UserAvatarChangedEvent;
use Ergonode\Account\Domain\Event\UserRoleChangedEvent;
use Ergonode\Account\Domain\Repository\RoleRepositoryInterface;
use Ergonode\Account\Domain\ValueObject\Privilege;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use JMS\Serializer\SerializerInterface;
use Webmozart\Assert\Assert;

/**
 */
class UserRoleChangedEventProjector implements DomainEventProjectorInterface
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
        return $event instanceof UserRoleChangedEvent;
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
        if (!$event instanceof UserRoleChangedEvent) {
            throw new UnsupportedEventException($event, UserRoleChangedEvent::class);
        }

        $role = $this->repository->load($event->getTo());

        Assert::notNull($role);
        /** @var Privilege[] $privileges */
        $privileges = $role->getPrivileges();

        $this->connection->beginTransaction();
        try {
            $this->connection->update(
                self::TABLE,
                [
                    'role_id' => $event->getTo()->getValue(),
                    'roles' => $this->serializer->serialize($privileges, 'json'),
                ],
                [
                    'id' => $aggregateId->getValue(),
                ]
            );
            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();
            throw $exception;
        }
    }
}
