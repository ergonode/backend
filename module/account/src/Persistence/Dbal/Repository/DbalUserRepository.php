<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Account\Persistence\Dbal\Repository;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Account\Domain\Repository\UserRepositoryInterface;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\Manager\ESManager;
use Webmozart\Assert\Assert;

/**
 */
class DbalUserRepository implements UserRepositoryInterface
{
    /**
     * @var ESManager
     */
    private ESManager $manager;

    /**
     * @param ESManager $manager
     */
    public function __construct(ESManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param UserId $id
     *
     * @return User|null
     *
     * @throws \ReflectionException
     */
    public function load(UserId $id): ?AbstractAggregateRoot
    {
        $aggregate = $this->manager->load($id);
        Assert::nullOrIsInstanceOf($aggregate, User::class);

        return $aggregate;
    }

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void
    {
        $this->manager->save($aggregateRoot);
    }
}
