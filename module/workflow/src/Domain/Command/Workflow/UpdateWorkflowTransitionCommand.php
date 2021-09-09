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

    private StatusId $source;

    private StatusId $destination;

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
        StatusId $source,
        StatusId $destination,
        array $roleIds = [],
        ?ConditionSetId $conditionSetId = null
    ) {
        $this->workflowId = $workflowId;
        $this->source = $source;
        $this->destination = $destination;
        $this->roleIds = $roleIds;
        $this->conditionSetId = $conditionSetId;
    }

    public function getWorkflowId(): WorkflowId
    {
        return $this->workflowId;
    }

    public function getSource(): StatusId
    {
        return $this->source;
    }

    public function getDestination(): StatusId
    {
        return $this->destination;
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
