<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Event\Workflow;

use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use JMS\Serializer\Annotation as JMS;

class WorkflowDeletedEvent extends AbstractDeleteEvent
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\WorkflowId")
     */
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
