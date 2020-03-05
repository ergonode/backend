<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Event\Workflow;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Workflow\Domain\Entity\Transition;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class WorkflowTransitionAddedEvent implements DomainEventInterface
{
    /**
     * @var WorkflowId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\WorkflowId")
     */
    private WorkflowId $id;

    /**
     * @var Transition
     *
     * @JMS\Type("Ergonode\Workflow\Domain\Entity\Transition")
     */
    private Transition $transition;

    /**
     * @param WorkflowId $id
     * @param Transition $transition
     */
    public function __construct(WorkflowId $id, Transition $transition)
    {
        $this->id = $id;
        $this->transition = $transition;
    }

    /**
     * @return WorkflowId
     */
    public function getAggregateId(): WorkflowId
    {
        return $this->id;
    }

    /**
     * @return Transition
     */
    public function getTransition(): Transition
    {
        return $this->transition;
    }
}
