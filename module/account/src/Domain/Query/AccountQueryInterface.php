<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Grid\DataSetInterface;

/**
 */
interface AccountQueryInterface
{
    /**
     * @return DataSetInterface
     */
    public function getDataSet(): DataSetInterface;

    /**
     * @param UserId $userId
     *
     * @return array
     */
    public function getUser(UserId $userId): array;

    /**
     * @param RoleId $roleId
     *
     * @return array
     */
    public function findUserIdByRoleId(RoleId $roleId): array;
}
