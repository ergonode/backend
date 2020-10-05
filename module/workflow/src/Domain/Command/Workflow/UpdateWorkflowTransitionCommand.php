<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Command\Workflow;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

/**
 */
class UpdateWorkflowTransitionCommand implements DomainCommandInterface
{
    /**
     * @var WorkflowId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\WorkflowId")
     */
    private WorkflowId $workflowId;

    /**
     * @var StatusId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\StatusId")
     */
    private StatusId $source;

    /**
     * @var StatusId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\StatusId")
     */
    private StatusId $destination;

    /**
     * @var RoleId[]
     *
     * @JMS\Type("array<Ergonode\SharedKernel\Domain\Aggregate\RoleId>")
     */
    private array $roleIds;

    /**
     * @var ConditionSetId|null
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId")
     */
    private ?ConditionSetId $conditionSetId;

    /**
     * @param WorkflowId          $workflowId
     * @param StatusId            $source
     * @param StatusId            $destination
     * @param RoleId[]            $roleIds
     * @param ConditionSetId|null $conditionSetId
     */
    public function __construct(
        WorkflowId $workflowId,
        StatusId $source,
        StatusId $destination,
        array $roleIds = [],
        ?ConditionSetId $conditionSetId = null
    ) {
        $this->workflowId = $workflowId;
        $this->source = $source;
        $this->destination = $destination;
        $this->roleIds = $roleIds;
        $this->conditionSetId = $conditionSetId;
    }

    /**
     * @return WorkflowId
     */
    public function getWorkflowId(): WorkflowId
    {
        return $this->workflowId;
    }

    /**
     * @return StatusId
     */
    public function getSource(): StatusId
    {
        return $this->source;
    }

    /**
     * @return StatusId
     */
    public function getDestination(): StatusId
    {
        return $this->destination;
    }

    /**
     * @return ConditionSetId|null
     */
    public function getConditionSetId(): ?ConditionSetId
    {
        return $this->conditionSetId;
    }

    /**
     * @return RoleId[]
     */
    public function getRoleIds(): array
    {
        return $this->roleIds;
    }
}
