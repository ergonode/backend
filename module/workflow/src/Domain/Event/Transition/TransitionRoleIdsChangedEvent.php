<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Event\Transition;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;

use Ergonode\SharedKernel\Domain\Aggregate\TransitionId;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Webmozart\Assert\Assert;

class TransitionRoleIdsChangedEvent implements AggregateEventInterface
{
    private WorkflowId $id;

    private TransitionId $transitionId;

    /**
     * @var RoleId[]
     */
    private array $roleIds;

    /**
     * @param RoleId[] $roleIds
     */
    public function __construct(WorkflowId $id, TransitionId $transitionId, array $roleIds = [])
    {
        Assert::allIsInstanceOf($roleIds, RoleId::class);

        $this->id = $id;
        $this->transitionId = $transitionId;
        $this->roleIds = $roleIds;
    }

    public function getAggregateId(): WorkflowId
    {
        return $this->id;
    }

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
