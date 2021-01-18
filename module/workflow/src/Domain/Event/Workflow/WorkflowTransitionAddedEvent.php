<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Event\Workflow;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\Workflow\Domain\Entity\Transition;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use JMS\Serializer\Annotation as JMS;

class WorkflowTransitionAddedEvent implements AggregateEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\WorkflowId")
     */
    private WorkflowId $id;

    /**
     * @JMS\Type("Ergonode\Workflow\Domain\Entity\Transition")
     */
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
