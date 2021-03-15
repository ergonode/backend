<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Event\Workflow;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

class WorkflowCreatedEvent implements AggregateEventInterface
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
     * @JMS\Type("string")
     */
    private string $class;

    /**
     * @var StatusId[]
     *
     * @JMS\Type("array<Ergonode\SharedKernel\Domain\Aggregate\StatusId>")
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
