<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Command\Status;

use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\Command\WorkflowCommandInterface;

class DeleteStatusCommand implements WorkflowCommandInterface
{
    private StatusId $id;

    public function __construct(StatusId $id)
    {
        $this->id = $id;
    }

    public function getId(): StatusId
    {
        return $this->id;
    }
}
