<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Persistence\Manager;

use Ergonode\Account\Domain\Entity\Role;
use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\Account\Domain\Event\Role\RoleDeletedEvent;
use Ergonode\EventSourcing\Persistance\Provider\AggregateRootProviderInterface;

/**
 */
class RoleAggregateRootManager implements RoleAggregateRootManagerInterface
{
    /**
     * @var AggregateRootProviderInterface
     */
    private $aggregateRootProvider;

    /**
     * @param AggregateRootProviderInterface $aggregateRootProvider
     */
    public function __construct(AggregateRootProviderInterface $aggregateRootProvider)
    {
        $this->aggregateRootProvider = $aggregateRootProvider;
    }

    /**
     * {@inheritDoc}
     */
    public function load(RoleId $id): Role
    {
        return $this->aggregateRootProvider->load($id, Role::class);
    }

    /**
     * {@inheritDoc}
     */
    public function exists(RoleId $id): bool
    {
        return $this->aggregateRootProvider->exists($id);
    }

    /**
     * {@inheritDoc}
     */
    public function save(Role $role): void
    {
        $this->aggregateRootProvider->save($role);
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function delete(Role $role): void
    {
        $role->apply(new RoleDeletedEvent());
        $this->aggregateRootProvider->save($role);

        $this->aggregateRootProvider->delete($role);
    }
}
