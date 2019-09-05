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
class RoleAggregateRootManager
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
     * @param RoleId $id
     *
     * @return Role
     */
    public function load(RoleId $id): Role
    {
        return $this->aggregateRootProvider->load($id, Role::class);
    }

    /**
     * @param RoleId $id
     *
     * @return bool
     */
    public function exists(RoleId $id): bool
    {
        return $this->aggregateRootProvider->exists($id);
    }

    /**
     * @param Role $role
     */
    public function save(Role $role): void
    {
        $this->aggregateRootProvider->save($role);
    }

    /**
     * @param Role $role
     *
     * @throws \Exception
     */
    public function delete(Role $role): void
    {
        $role->apply(new RoleDeletedEvent());
        $this->aggregateRootProvider->delete($role);
    }
}
