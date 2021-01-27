<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Event\Workflow;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

class WorkflowTransitionRemovedEvent implements AggregateEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\WorkflowId")
     */
    private WorkflowId $id;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\StatusId")
     */
    private StatusId $source;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\StatusId")
     */
    private StatusId $destination;

    public function __construct(WorkflowId $id, StatusId $source, StatusId $destination)
    {
        $this->id = $id;
        $this->source = $source;
        $this->destination = $destination;
    }

    public function getAggregateId(): WorkflowId
    {
        return $this->id;
    }


    public function getSource(): StatusId
    {
        return $this->source;
    }

    public function getDestination(): StatusId
    {
        return $this->destination;
    }
}
