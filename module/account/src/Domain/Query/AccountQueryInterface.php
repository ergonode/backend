<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Query;

use Ergonode\Grid\DataSetInterface;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;

interface AccountQueryInterface
{
    public function getDataSet(): DataSetInterface;

    /**
     * @return array | null
     */
    public function getUser(UserId $userId): ?array;

    /**
     * @return UserId[]
     */
    public function findUserIdByRoleId(RoleId $roleId): array;
}
