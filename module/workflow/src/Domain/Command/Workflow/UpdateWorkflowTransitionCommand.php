<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Command\Workflow;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\Command\WorkflowCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

class UpdateWorkflowTransitionCommand implements WorkflowCommandInterface
{
    private WorkflowId $workflowId;

    private StatusId $from;

    private StatusId $to;

    /**
     * @var RoleId[]
     */
    private array $roleIds;

    private ?ConditionSetId $conditionSetId;

    /**
     * @param RoleId[] $roleIds
     */
    public function __construct(
        WorkflowId $workflowId,
        StatusId $from,
        StatusId $to,
        array $roleIds = [],
        ?ConditionSetId $conditionSetId = null
    ) {
        $this->workflowId = $workflowId;
        $this->from = $from;
        $this->to = $to;
        $this->roleIds = $roleIds;
        $this->conditionSetId = $conditionSetId;
    }

    public function getWorkflowId(): WorkflowId
    {
        return $this->workflowId;
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
     * @deprecated
     */
    public function getSource(): StatusId
    {
        @trigger_error(
            sprintf(
                '%1$s::getSource is deprecated and will be removed in 2.0, use %1$s::getFrom instead',
                self::class,
            ),
            \E_USER_DEPRECATED
        );

        return $this->getFrom();
    }

    /**
     * @deprecated
     */
    public function getDestination(): StatusId
    {
        @trigger_error(
            sprintf(
                '%1$s::getDestination is deprecated and will be removed in 2.0, use %1$s::getTo instead',
                self::class,
            ),
            \E_USER_DEPRECATED
        );

        return $this->getTo();
    }

    public function getConditionSetId(): ?ConditionSetId
    {
        return $this->conditionSetId;
    }

    /**
     * @return RoleId[]
     */
    public function getRoleIds(): array
    {
        return $this->roleIds;
    }
}
