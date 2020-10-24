<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Grid\DataSetInterface;

interface RoleQueryInterface
{
    public function getDataSet(): DataSetInterface;

    /**
     * @return string[]
     */
    public function getDictionary(): array;

    public function getRoleUsersCount(RoleId $id): int;

    /**
     * @return UserId[]
     */
    public function getAllRoleUsers(RoleId $id): array;

    /**
     * @return array
     */
    public function autocomplete(
        string $search = null,
        int $limit = null,
        string $field = null,
        ?string $order = 'ASC'
    ): array;

    public function findIdByRoleName(string $name): ?RoleId;
}
