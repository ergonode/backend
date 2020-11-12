<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Command\Workflow;

use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

class CreateWorkflowCommand implements CreateWorkflowCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\WorkflowId")
     */
    private WorkflowId $id;

    /**
     * @JMS\Type("string")
     */
    private string $code;

    /**
     * @var StatusId[]
     *
     * @JMS\Type("array<Ergonode\SharedKernel\Domain\Aggregate\StatusId>")
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
