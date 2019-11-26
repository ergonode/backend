<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Event\Transition;

use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TransitionRoleIdsChangedEvent implements DomainEventInterface
{
    /**
     * @var RoleId[]
     *
     * @JMS\Type("array<Ergonode\Account\Domain\Entity\RoleId>")
     */
    private $roleIds;

    /**
     * @param RoleId[] $roleIds
     */
    public function __construct(array $roleIds = [])
    {
        $this->roleIds = $roleIds;
    }

    /**
     * @return RoleId[]
     */
    public function getRoleIds(): array
    {
        return $this->roleIds;
    }
}
