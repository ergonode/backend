<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Event\Workflow;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\Workflow\Domain\Entity\Transition;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;

class WorkflowTransitionAddedEvent implements AggregateEventInterface
{
    private WorkflowId $id;

    private Transition $transition;

    public function __construct(WorkflowId $id, Transition $transition)
    {
        $this->id = $id;
        $this->transition = $transition;
    }

    public function getAggregateId(): WorkflowId
    {
        return $this->id;
    }

    public function getTransition(): Transition
    {
        return $this->transition;
    }
}
