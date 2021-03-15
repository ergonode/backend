<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Command\Workflow;

use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\Command\WorkflowCommandInterface;

interface CreateWorkflowCommandInterface extends WorkflowCommandInterface
{
    public function getId(): WorkflowId;
}
