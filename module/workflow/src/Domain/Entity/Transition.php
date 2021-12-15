<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Entity;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\TransitionId;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\Condition\WorkflowConditionInterface;
use Webmozart\Assert\Assert;

class Transition
{
    private TransitionId $id;

    private StatusId $from;

    private StatusId $to;

    /**
     * @var WorkflowConditionInterface[]
     */
    private array $conditions = [];

    /**
     * @var RoleId[]
     */
    private array $roleIds;

    /**
     * @param RoleId[] $roleIds
     * @param WorkflowConditionInterface[] $conditions
     */
    public function __construct(
        TransitionId $id,
        StatusId $from,
        StatusId $to,
        array $roleIds = [],
        array $conditions = []
    ) {
        Assert::allIsInstanceOf($roleIds, RoleId::class);
        Assert::allIsInstanceOf($conditions, WorkflowConditionInterface::class);

        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
        $this->roleIds = $roleIds;
        $this->conditions = $conditions;
    }

    public function getId(): TransitionId
    {
        return $this->id;
    }

    public function getFrom(): StatusId
    {
        return $this->from;
    }

    public function getTo(): StatusId
    {
        return $this->to;
    }

    /**
     * @return RoleId[]
     */
    public function getRoleIds(): array
    {
        return $this->roleIds;
    }

    /**
     * @return WorkflowConditionInterface[]
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }
}
