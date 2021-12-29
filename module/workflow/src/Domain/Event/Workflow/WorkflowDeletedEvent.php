<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Event\Workflow;

use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;

class WorkflowDeletedEvent extends AbstractDeleteEvent
{
    private WorkflowId $id;

    public function __construct(WorkflowId $id)
    {
        $this->id = $id;
    }

    public function getAggregateId(): WorkflowId
    {
        return $this->id;
    }
}
