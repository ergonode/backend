<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Persistence\Dbal\Projector\User;

use Doctrine\DBAL\Connection;
use Ergonode\Account\Domain\Event\User\UserRoleChangedEvent;
use Ergonode\Account\Domain\Repository\RoleRepositoryInterface;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
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
     * @param Connection              $connection
     * @param RoleRepositoryInterface $repository
     */
    public function __construct(Connection $connection, RoleRepositoryInterface $repository)
    {
        $this->connection = $connection;
        $this->repository = $repository;
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
     * @throws UnsupportedEventException
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

        $this->connection->transactional(function () use ($event, $aggregateId) {
            $this->connection->update(
                self::TABLE,
                [
                    'role_id' => $event->getTo()->getValue(),
                ],
                [
                    'id' => $aggregateId->getValue(),
                ]
            );
        });
    }
}
