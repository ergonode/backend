<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Persistence\Manager;

use Ergonode\Account\Domain\Entity\Role;
use Ergonode\Account\Domain\Entity\RoleId;

/**
 */
interface RoleAggregateRootManagerInterface
{
    /**
     * @param RoleId $id
     *
     * @return Role
     */
    public function load(RoleId $id): Role;

    /**
     * @param RoleId $id
     *
     * @return bool
     */
    public function exists(RoleId $id): bool;

    /**
     * @param Role $role
     */
    public function save(Role $role): void;

    /**
     * @param Role $role
     */
    public function delete(Role $role): void;
}
