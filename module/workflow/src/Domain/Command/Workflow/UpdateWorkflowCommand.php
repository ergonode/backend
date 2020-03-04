<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Command\Workflow;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

/**
 */
class UpdateWorkflowCommand implements DomainCommandInterface
{
    /**
     * @var WorkflowId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\WorkflowId")
     */
    private WorkflowId $id;

    /**
     * @var StatusCode[]
     *
     * @JMS\Type("array<Ergonode\Workflow\Domain\ValueObject\StatusCode>")
     */
    private array $statuses;


    /**
     * @param WorkflowId $id
     * @param array      $statuses
     */
    public function __construct(WorkflowId $id, array $statuses = [])
    {
        Assert::allIsInstanceOf($statuses, StatusCode::class);

        $this->id = $id;
        $this->statuses = $statuses;
    }

    /**
     * @return WorkflowId
     */
    public function getId(): WorkflowId
    {
        return $this->id;
    }

    /**
     * @return StatusCode[]
     */
    public function getStatuses(): array
    {
        return $this->statuses;
    }
}
