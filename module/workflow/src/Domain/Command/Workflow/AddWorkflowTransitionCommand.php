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
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use JMS\Serializer\Annotation as JMS;

/**
 */
class AddWorkflowTransitionCommand implements DomainCommandInterface
{
    /**
     * @var WorkflowId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\WorkflowId")
     */
    private WorkflowId $workflowId;

    /**
     * @var StatusCode
     *
     * @JMS\Type("Ergonode\Workflow\Domain\ValueObject\StatusCode")
     */
    private StatusCode $source;

    /**
     * @var StatusCode
     *
     * @JMS\Type("Ergonode\Workflow\Domain\ValueObject\StatusCode")
     */
    private StatusCode $destination;

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
     * @param StatusCode          $source
     * @param StatusCode          $destination
     * @param RoleId[]            $roleIds
     * @param ConditionSetId|null $conditionSetId
     */
    public function __construct(
        WorkflowId $workflowId,
        StatusCode $source,
        StatusCode $destination,
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
     * @return StatusCode
     */
    public function getSource(): StatusCode
    {
        return $this->source;
    }

    /**
     * @return StatusCode
     */
    public function getDestination(): StatusCode
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
