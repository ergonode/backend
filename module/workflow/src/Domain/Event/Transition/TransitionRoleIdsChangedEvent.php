<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Event\Transition;

use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Workflow\Domain\Entity\TransitionId;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

/**
 */
class TransitionRoleIdsChangedEvent implements DomainEventInterface
{
    /**
     * @var TransitionId
     *
     * @JMS\Type("Ergonode\Workflow\Domain\Entity\TransitionId")
     */
    private $transitionId;

    /**
     * @var RoleId[]
     *
     * @JMS\Type("array<Ergonode\Account\Domain\Entity\RoleId>")
     */
    private $roleIds;

    /**
     * @param TransitionId $transitionId
     * @param RoleId[]     $roleIds
     */
    public function __construct(TransitionId $transitionId, array $roleIds = [])
    {
        Assert::allIsInstanceOf($roleIds, RoleId::class);

        $this->transitionId = $transitionId;
        $this->roleIds = $roleIds;
    }

    /**
     * @return TransitionId
     */
    public function getTransitionId(): TransitionId
    {
        return $this->transitionId;
    }

    /**
     * @param TransitionId $transitionId
     */
    public function setTransitionId(TransitionId $transitionId): void
    {
        $this->transitionId = $transitionId;
    }

    /**
     * @return RoleId[]
     */
    public function getRoleIds(): array
    {
        return $this->roleIds;
    }
}
