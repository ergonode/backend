<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Repository;

use Ergonode\Account\Domain\Entity\Role;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;

interface RoleRepositoryInterface
{
    public function load(RoleId $id): ?Role;

    public function save(Role $aggregateRoot): void;

    public function delete(Role $aggregateRoot): void;
}
