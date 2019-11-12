<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Query;

use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Grid\DataSetInterface;

/**
 */
interface RoleQueryInterface
{
    /**
     * @return DataSetInterface
     */
    public function getDataSet(): DataSetInterface;

    /**
     * @return string[]
     */
    public function getDictionary(): array;

    /**
     * @param RoleId $id
     *
     * @return int
     */
    public function getRoleUsersCount(RoleId $id): int;

    /**
     * @param RoleId $id
     *
     * @return UserId[]
     */
    public function getAllRoleUsers(RoleId $id): array;
}
