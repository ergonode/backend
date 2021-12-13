<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Entity;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\SharedKernel\Domain\Aggregate\TransitionId;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\Condition\WorkflowConditionInterface;

class Transition
{
    private TransitionId $id;

    private StatusId $from;

    private StatusId $to;

    /**
     * @deprecated
     */
    private ?ConditionSetId $conditionSetId;

    /**
     * @var WorkflowConditionInterface[]
     */
    private array $conditions;

    /**
     * @var RoleId[]
     */
    private array $roleIds;

    /**
     * @param RoleId[] $roleIds
     */
    public function __construct(
        TransitionId $id,
        StatusId $from,
        StatusId $to,
        array $roleIds = [],
        ?ConditionSetId $conditionSetId = null
    ) {
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
        $this->conditionSetId = $conditionSetId;
        $this->roleIds = $roleIds;
        $this->conditions = [];
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
     * @deprecated
     */
    public function getConditionSetId(): ?ConditionSetId
    {
        return $this->conditionSetId;
    }

    /**
     * @throws \Exception
     *
     * @deprecated
     */
    public function changeConditionSetId(?ConditionSetId $conditionSetId = null): void
    {
        $this->conditionSetId = $conditionSetId;
    }

    /**
     * @return WorkflowConditionInterface[]
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    public function changeConditions(array $conditions): void
    {
        Assert::allIsInstanceOf($conditions, WorkflowConditionInterface::class);

        $this->conditions = $conditions;
    }

    /**
     * @param array $roleIds
     *
     * @throws \Exception
     */
    public function changeRoleIds(array $roleIds = []): void
    {
        Assert::allIsInstanceOf($roleIds, RoleId::class);

        $this->roleIds = $roleIds;
    }
}
