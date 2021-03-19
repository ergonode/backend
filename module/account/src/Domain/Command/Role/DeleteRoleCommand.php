<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Command\Role;

use Ergonode\Account\Domain\Command\AccountCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;

class DeleteRoleCommand implements AccountCommandInterface
{
    private RoleId $id;

    public function __construct(RoleId $id)
    {
        $this->id = $id;
    }

    public function getId(): RoleId
    {
        return $this->id;
    }
}
