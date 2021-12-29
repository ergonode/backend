<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Command\Status;

use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\Command\WorkflowCommandInterface;

class SetStatusOrderCommand implements WorkflowCommandInterface
{
    private array $statusIds;

    public function __construct(array $statusIds)
    {
        $this->statusIds = $statusIds;
    }

    /**
     * @return StatusId[]
     */
    public function getStatusIds(): array
    {
        return $this->statusIds;
    }
}
