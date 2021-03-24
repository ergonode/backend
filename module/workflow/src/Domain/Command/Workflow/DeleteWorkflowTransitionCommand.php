<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Command\Workflow;

use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\Command\WorkflowCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

class DeleteWorkflowTransitionCommand implements WorkflowCommandInterface
{
    private WorkflowId $workflowId;

    private StatusId $source;

    private StatusId $destination;

    public function __construct(
        WorkflowId $workflowId,
        StatusId $source,
        StatusId $destination
    ) {
        $this->workflowId = $workflowId;
        $this->source = $source;
        $this->destination = $destination;
    }

    public function getWorkflowId(): WorkflowId
    {
        return $this->workflowId;
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
