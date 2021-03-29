<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Event\Workflow;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

class WorkflowTransitionRemovedEvent implements AggregateEventInterface
{
    private WorkflowId $id;

    private StatusId $source;

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
