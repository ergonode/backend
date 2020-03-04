<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Event\Transition;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\TransitionId;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

/**
 */
class TransitionRoleIdsChangedEvent implements DomainEventInterface
{
    /**
     * @var WorkflowId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\WorkflowId")
     */
    private WorkflowId $id;

    /**
     * @var TransitionId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TransitionId")
     */
    private TransitionId $transitionId;

    /**
     * @var RoleId[]
     *
     * @JMS\Type("array<Ergonode\SharedKernel\Domain\Aggregate\RoleId>")
     */
    private array $roleIds;

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
     * @return WorkflowId
     */
    public function getAggregateId(): WorkflowId
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
