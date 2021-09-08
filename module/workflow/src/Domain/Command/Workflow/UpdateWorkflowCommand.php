<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Command\Workflow;

use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

class UpdateWorkflowCommand implements UpdateWorkflowCommandInterface
{
    private WorkflowId $id;

    /**
     * @var StatusId[]
     */
    private array $statuses;

    /**
     * @param StatusId[] $statuses
     */
    public function __construct(WorkflowId $id, array $statuses = [])
    {
        Assert::allIsInstanceOf($statuses, StatusId::class);

        $this->id = $id;
        $this->statuses = $statuses;
    }

    public function getId(): WorkflowId
    {
        return $this->id;
    }

    /**
     * @return StatusId[]
     */
    public function getStatuses(): array
    {
        return $this->statuses;
    }
}
