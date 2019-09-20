<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Command\Workflow;

use Ergonode\Workflow\Domain\Entity\StatusId;
use Ergonode\Workflow\Domain\Entity\WorkflowId;
use Ergonode\Workflow\Domain\ValueObject\Status;
use Ergonode\Workflow\Domain\ValueObject\Transition;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

/**
 */
class CreateWorkflowCommand
{
    /**
     * @var WorkflowId
     *
     * @JMS\Type("Ergonode\Workflow\Domain\Entity\WorkflowId")
     */
    private $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $code;

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
     * @param string $code
     * @param array  $statuses
     * @param array  $transitions
     *
     * @throws \Exception
     */
    public function __construct(string $code, array $statuses = [], array $transitions = [])
    {
        Assert::allIsInstanceOf($statuses, StatusId::class);
        Assert::allIsInstanceOf($transitions, Transition::class);

        $this->id = WorkflowId::fromCode($code);
        $this->code = $code;
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
     * @return string
     */
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

    /**
     * @return Transition[]
     */
    public function getTransitions(): array
    {
        return $this->transitions;
    }
}
