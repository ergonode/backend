<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Event\Workflow;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

class WorkflowCreatedEvent implements AggregateEventInterface
{
    private WorkflowId $id;

    private string $code;

    private string $class;

    /**
     * @var StatusId[]
     */
    private array $statuses;

    /**
     * @param StatusId[] $statuses
     */
    public function __construct(WorkflowId $id, string $class, string $code, array $statuses = [])
    {
        Assert::allIsInstanceOf($statuses, StatusId::class);

        $this->id = $id;
        $this->code = $code;
        $this->class = $class;
        $this->statuses = $statuses;
    }

    public function getAggregateId(): WorkflowId
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return StatusId[]
     */
    public function getStatuses(): array
    {
        return $this->statuses;
    }
}
