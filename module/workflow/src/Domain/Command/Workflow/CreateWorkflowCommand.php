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

class CreateWorkflowCommand implements CreateWorkflowCommandInterface
{
    private WorkflowId $id;

    private string $code;

    /**
     * @var StatusId[]
     */
    private array $statuses;

    /**
     * @param array $statuses
     */
    public function __construct(WorkflowId $id, string $code, array $statuses = [])
    {
        Assert::allIsInstanceOf($statuses, StatusId::class);

        $this->id = $id;
        $this->code = $code;
        $this->statuses = $statuses;
    }

    public function getId(): WorkflowId
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return StatusId[]
     */
    public function getStatuses(): array
    {
        return $this->statuses;
    }
}
