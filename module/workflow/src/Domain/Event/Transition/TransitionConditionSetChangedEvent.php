<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Event\Transition;

use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\SharedKernel\Domain\Aggregate\TransitionId;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;

class TransitionConditionSetChangedEvent implements AggregateEventInterface
{
    private WorkflowId $id;

    private TransitionId $transitionId;

    private ?ConditionSetId $conditionSetId;

    public function __construct(WorkflowId $id, TransitionId $transitionId, ?ConditionSetId $conditionSetId = null)
    {
        $this->id = $id;
        $this->transitionId = $transitionId;
        $this->conditionSetId = $conditionSetId;
    }

    public function getAggregateId(): WorkflowId
    {
        return $this->id;
    }

    public function getTransitionId(): TransitionId
    {
        return $this->transitionId;
    }

    public function getConditionSetId(): ?ConditionSetId
    {
        return $this->conditionSetId;
    }
}
