<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Command\Workflow;

use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Workflow\Domain\Entity\WorkflowId;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use JMS\Serializer\Annotation as JMS;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;

/**
 */
class AddWorkflowTransitionCommand implements DomainCommandInterface
{
    /**
     * @var WorkflowId
     *
     * @JMS\Type("Ergonode\Workflow\Domain\Entity\WorkflowId")
     */
    private $workflowId;

    /**
     * @var StatusCode
     *
     * @JMS\Type("Ergonode\Workflow\Domain\ValueObject\StatusCode")
     */
    private $source;

    /**
     * @var StatusCode;
     *
     * @JMS\Type("Ergonode\Workflow\Domain\ValueObject\StatusCode")
     */
    private $destination;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private $name;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private $description;

    /**
     * @var RoleId[]
     *
     * @JMS\Type("array<Ergonode\Account\Domain\Entity\RoleId>")
     */
    private $roleIds;

    /**
     * @var ConditionSetId|null
     *
     * @JMS\Type("Ergonode\Condition\Domain\Entity\ConditionSetId")
     */
    private $conditionSetId;

    /**
     * @param WorkflowId          $workflowId
     * @param StatusCode          $source
     * @param StatusCode          $destination
     * @param TranslatableString  $name
     * @param TranslatableString  $description
     * @param RoleId[]            $roleIds
     * @param ConditionSetId|null $conditionSetId
     */
    public function __construct(
        WorkflowId $workflowId,
        StatusCode $source,
        StatusCode $destination,
        TranslatableString $name,
        TranslatableString $description,
        array $roleIds = [],
        ?ConditionSetId $conditionSetId = null
    ) {
        $this->workflowId = $workflowId;
        $this->source = $source;
        $this->destination = $destination;
        $this->name = $name;
        $this->description = $description;
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
     * @return TranslatableString
     */
    public function getName(): TranslatableString
    {
        return $this->name;
    }

    /**
     * @return TranslatableString
     */
    public function getDescription(): TranslatableString
    {
        return $this->description;
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
