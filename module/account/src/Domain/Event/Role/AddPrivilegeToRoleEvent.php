<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Event\Role;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\Account\Domain\ValueObject\Privilege;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class AddPrivilegeToRoleEvent implements DomainEventInterface
{
    /**
     * @var RoleId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\RoleId")
     */
    private $id;

    /**
     * @var Privilege
     *
     * @JMS\Type("Ergonode\Account\Domain\ValueObject\Privilege")
     */
    private $privilege;

    /**
     * @param RoleId    $id
     * @param Privilege $privilege
     */
    public function __construct(RoleId $id, Privilege $privilege)
    {
        $this->id = $id;
        $this->privilege = $privilege;
    }

    /**
     * @return RoleId
     */
    public function getAggregateId(): RoleId
    {
        return $this->id;
    }

    /**
     * @return Privilege
     */
    public function getPrivilege(): Privilege
    {
        return $this->privilege;
    }
}
