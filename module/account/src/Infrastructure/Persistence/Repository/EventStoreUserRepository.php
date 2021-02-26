<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Persistence\Repository;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManager;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Webmozart\Assert\Assert;

class EventStoreUserRepository implements UserRepositoryInterface
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
    public function load(UserId $id): ?User
    {
        /** @var User|null $aggregate */
        $aggregate = $this->manager->load($id);
        Assert::nullOrIsInstanceOf($aggregate, User::class);

        return $aggregate;
    }

    public function save(User $aggregateRoot): void
    {
        $this->manager->save($aggregateRoot);
    }
}
