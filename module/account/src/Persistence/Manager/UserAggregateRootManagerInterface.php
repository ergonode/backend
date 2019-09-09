<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Persistence\Manager;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\Entity\UserId;

/**
 */
interface UserAggregateRootManagerInterface
{
    /**
     * @param UserId $id
     *
     * @return User
     */
    public function load(UserId $id): User;

    /**
     * @param UserId $id
     *
     * @return bool
     */
    public function exists(UserId $id): bool;

    /**
     * @param User $role
     */
    public function save(User $role): void;

    /**
     * @param User $role
     */
    public function delete(User $role): void;
}
