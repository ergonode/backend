<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Command\Workflow;

use Ergonode\Workflow\Domain\Entity\StatusId;
use Ergonode\Workflow\Domain\Entity\WorkflowId;
use Ergonode\Workflow\Domain\ValueObject\Transition;
use Webmozart\Assert\Assert;
use JMS\Serializer\Annotation as JMS;

/**
 */
class UpdateWorkflowCommand
{
    /**
     * @var WorkflowId
     *
     * @JMS\Type("Ergonode\Workflow\Domain\Entity\WorkflowId")
     */
    private $id;

    /**
     * @var StatusId[]
     *
     * @JMS\Type("array<Ergonode\Workflow\Domain\Entity\StatusId>")
     */
    private $statuses;

    /**
     * @var Transition[]
     *
     * @JMS\Type("array<Ergonode\Workflow\Domain\ValueObject\Transition>")
     */
    private $transitions;

    /**
     * @param WorkflowId $id
     * @param array      $statuses
     * @param array      $transitions
     */
    public function __construct(WorkflowId $id, array $statuses = [], array $transitions = [])
    {
        Assert::allIsInstanceOf($statuses, StatusId::class);
        Assert::allIsInstanceOf($transitions, Transition::class);

        $this->id = $id;
        $this->statuses = $statuses;
        $this->transitions = $transitions;
    }

    /**
     * @return WorkflowId
     */
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

    /**
     * @return Transition[]
     */
    public function getTransitions(): array
    {
        return $this->transitions;
    }
}
