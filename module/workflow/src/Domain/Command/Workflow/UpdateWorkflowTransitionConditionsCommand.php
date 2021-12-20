<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Command\Workflow;

use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\Condition\WorkflowConditionInterface;
use Ergonode\Workflow\Domain\Command\WorkflowCommandInterface;
use Webmozart\Assert\Assert;

class UpdateWorkflowTransitionConditionsCommand implements WorkflowCommandInterface
{
    private WorkflowId $id;

    private StatusId $from;

    private StatusId $to;

    /**
     * @var WorkflowConditionInterface[]
     */
    private array $conditions;

    /**
     * @param WorkflowConditionInterface[] $conditions
     */
    public function __construct(WorkflowId $id, StatusId $from, StatusId $to, array $conditions)
    {
        Assert::allIsInstanceOf($conditions, WorkflowConditionInterface::class);

        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
        $this->conditions = $conditions;
    }

    public function getId(): WorkflowId
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

    public function getConditions(): array
    {
        return $this->conditions;
    }
}
