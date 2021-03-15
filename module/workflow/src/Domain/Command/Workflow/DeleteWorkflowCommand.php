<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Command\Workflow;

use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\Command\WorkflowCommandInterface;

class DeleteWorkflowCommand implements WorkflowCommandInterface
{
    private WorkflowId $id;

    public function __construct(WorkflowId $id)
    {
        $this->id = $id;
    }

    public function getId(): WorkflowId
    {
        return $this->id;
    }
}
