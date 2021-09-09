<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Event\Workflow;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

class WorkflowStatusAddedEvent implements AggregateEventInterface
{
    private WorkflowId $id;

    private StatusId $statusId;

    public function __construct(WorkflowId $id, StatusId $statusId)
    {
        $this->id = $id;
        $this->statusId = $statusId;
    }

    public function getAggregateId(): WorkflowId
    {
        return $this->id;
    }


    public function getStatusId(): StatusId
    {
        return $this->statusId;
    }
}
