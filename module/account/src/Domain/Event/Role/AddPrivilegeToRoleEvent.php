<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Event\Role;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\Account\Domain\ValueObject\Privilege;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use JMS\Serializer\Annotation as JMS;

class AddPrivilegeToRoleEvent implements AggregateEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\RoleId")
     */
    private RoleId $id;

    /**
     * @JMS\Type("Ergonode\Account\Domain\ValueObject\Privilege")
     */
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
