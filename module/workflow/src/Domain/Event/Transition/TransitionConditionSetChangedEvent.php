<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Event\Transition;

use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\SharedKernel\Domain\Aggregate\TransitionId;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use JMS\Serializer\Annotation as JMS;

class TransitionConditionSetChangedEvent implements AggregateEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\WorkflowId")
     */
    private WorkflowId $id;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TransitionId")
     */
    private TransitionId $transitionId;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId")
     */
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
