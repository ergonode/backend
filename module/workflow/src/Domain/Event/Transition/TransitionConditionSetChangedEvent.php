<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Event\Transition;

use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Workflow\Domain\Entity\TransitionId;
use Ergonode\Workflow\Domain\Entity\WorkflowId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TransitionConditionSetChangedEvent implements DomainEventInterface
{
    /**
     * @var WorkflowId
     *
     * @JMS\Type("Ergonode\Workflow\Domain\Entity\WorkflowId")
     */
    private $id;

    /**
     * @var TransitionId
     *
     * @JMS\Type("Ergonode\Workflow\Domain\Entity\TransitionId")
     */
    private $transitionId;

    /**
     * @var ConditionSetId|null
     *
     * @JMS\Type("Ergonode\Condition\Domain\Entity\ConditionSetId")
     */
    private $conditionSetId;

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
     * @return AbstractId|WorkflowId
     */
    public function getAggregateId(): AbstractId
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
