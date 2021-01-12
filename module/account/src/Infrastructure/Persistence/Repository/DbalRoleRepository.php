<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Persistence\Repository;

use Doctrine\DBAL\DBALException;
use Ergonode\Account\Domain\Entity\Role;
use Ergonode\Account\Domain\Event\Role\RoleDeletedEvent;
use Ergonode\Account\Domain\Repository\RoleRepositoryInterface;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManager;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Webmozart\Assert\Assert;

class DbalRoleRepository implements RoleRepositoryInterface
{
    private EventStoreManager $manager;

    public function __construct(EventStoreManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     *
     * @throws \ReflectionException
     */
    public function load(RoleId $id): ?Role
    {
        /** @var Role|null $aggregate */
        $aggregate = $this->manager->load($id);
        Assert::nullOrIsInstanceOf($aggregate, Role::class);

        return $aggregate;
    }

    /**
     * @throws DBALException
     */
    public function save(Role $aggregateRoot): void
    {
        $this->manager->save($aggregateRoot);
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function delete(Role $aggregateRoot): void
    {
        $aggregateRoot->apply(new RoleDeletedEvent($aggregateRoot->getId()));
        $this->save($aggregateRoot);

        $this->manager->delete($aggregateRoot);
    }
}
