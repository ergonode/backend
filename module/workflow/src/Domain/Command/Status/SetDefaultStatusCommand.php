<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Command\Status;

use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\Command\WorkflowCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

class SetDefaultStatusCommand implements WorkflowCommandInterface
{
    private WorkflowId $workflowId;

    private StatusId $statusId;

    public function __construct(WorkflowId $workflowId, StatusId $statusId)
    {
        $this->workflowId = $workflowId;
        $this->statusId = $statusId;
    }

    public function getWorkflowId(): WorkflowId
    {
        return $this->workflowId;
    }

    public function getStatusId(): StatusId
    {
        return $this->statusId;
    }
}
