<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Event\Transition;

use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Workflow\Domain\Entity\TransitionId;
use Ergonode\Workflow\Domain\Entity\WorkflowId;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

/**
 */
class TransitionRoleIdsChangedEvent implements DomainEventInterface
{
    /**
     * @var WorkflowId
     *
     * @JMS\Type("Ergonode\Workflow\Domain\Entity\WorkflowId")
     */
    private $id;

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
     * @param WorkflowId   $id
     * @param TransitionId $transitionId
     * @param RoleId[]     $roleIds
     */
    public function __construct(WorkflowId $id, TransitionId $transitionId, array $roleIds = [])
    {
        Assert::allIsInstanceOf($roleIds, RoleId::class);

        $this->id = $id;
        $this->transitionId = $transitionId;
        $this->roleIds = $roleIds;
    }

    /**
     * @return AbstractId|WorkflowId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return TransitionId
     */
    public function getTransitionId(): TransitionId
    {
        return $this->transitionId;
    }

    /**
     * @return RoleId[]
     */
    public function getRoleIds(): array
    {
        return $this->roleIds;
    }
}
