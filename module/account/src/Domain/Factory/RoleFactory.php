<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Factory;

use Ergonode\Account\Domain\Entity\Role;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\Account\Domain\ValueObject\Privilege;
use Webmozart\Assert\Assert;

/**
 */
class RoleFactory
{
    /**
     * @param RoleId      $id
     * @param string      $name
     * @param string      $description
     * @param Privilege[] $privileges
     *
     * @return Role
     */
    public function create(RoleId $id, string $name, string $description, array $privileges = []): Role
    {
        Assert::allIsInstanceOf($privileges, Privilege::class);

        return new Role($id, $name, $description, $privileges);
    }
}
