<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Event\Workflow;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

class WorkflowStatusAddedEvent implements AggregateEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\WorkflowId")
     */
    private WorkflowId $id;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\StatusId")
     */
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
