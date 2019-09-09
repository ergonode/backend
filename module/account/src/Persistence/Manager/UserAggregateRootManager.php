<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Persistence\Manager;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Account\Domain\Event\User\UserDeletedEvent;
use Ergonode\EventSourcing\Persistance\Provider\AggregateRootProviderInterface;

/**
 */
class UserAggregateRootManager implements UserAggregateRootManagerInterface
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
    public function load(UserId $id): User
    {
        return $this->aggregateRootProvider->load($id, User::class);
    }

    /**
     * {@inheritDoc}
     */
    public function exists(UserId $id): bool
    {
        return $this->aggregateRootProvider->exists($id);
    }

    /**
     * {@inheritDoc}
     */
    public function save(User $role): void
    {
        $this->aggregateRootProvider->save($role);
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function delete(User $role): void
    {
        $role->apply(new UserDeletedEvent());
        $this->aggregateRootProvider->save($role);

        $this->aggregateRootProvider->delete($role);
    }
}
