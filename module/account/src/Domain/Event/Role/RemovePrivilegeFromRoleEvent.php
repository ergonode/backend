<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Event\Role;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\Account\Domain\ValueObject\Privilege;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;

class RemovePrivilegeFromRoleEvent implements AggregateEventInterface
{
    private RoleId $id;

    private Privilege $privilege;

    public function __construct(RoleId $id, Privilege $privilege)
    {
        $this->id = $id;
        $this->privilege = $privilege;
    }

    public function getAggregateId(): RoleId
    {
        return $this->id;
    }

    public function getPrivilege(): Privilege
    {
        return $this->privilege;
    }
}
