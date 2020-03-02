<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Event\Transition;

use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\TransitionId;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TransitionConditionSetChangedEvent implements DomainEventInterface
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
     * @var ConditionSetId|null
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId")
     */
    private ?ConditionSetId $conditionSetId;

    /**
     * @param WorkflowId          $id
     * @param TransitionId        $transitionId
     * @param ConditionSetId|null $conditionSetId
     */
    public function __construct(WorkflowId $id, TransitionId $transitionId, ?ConditionSetId $conditionSetId = null)
    {
        $this->id = $id;
        $this->transitionId = $transitionId;
        $this->conditionSetId = $conditionSetId;
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
     * @return ConditionSetId|null
     */
    public function getConditionSetId(): ?ConditionSetId
    {
        return $this->conditionSetId;
    }
}
