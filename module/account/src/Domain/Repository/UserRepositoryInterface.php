<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Repository;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;

/**
 */
interface UserRepositoryInterface
{
    /**
     * @param UserId $id
     *
     * @return User|null
     */
    public function load(UserId $id): ?AbstractAggregateRoot;

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void;
}
