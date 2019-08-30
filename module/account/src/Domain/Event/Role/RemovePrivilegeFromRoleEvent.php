<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Event\Role;

use Ergonode\Account\Domain\ValueObject\Privilege;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class RemovePrivilegeFromRoleEvent implements DomainEventInterface
{
    /**
     * @var Privilege
     *
     * @JMS\Type("Ergonode\Account\Domain\ValueObject\Privilege")
     */
    private $privilege;

    /**
     * @param Privilege $privilege
     */
    public function __construct(Privilege $privilege)
    {
        $this->privilege = $privilege;
    }

    /**
     * @return Privilege
     */
    public function getPrivilege(): Privilege
    {
        return $this->privilege;
    }
}
