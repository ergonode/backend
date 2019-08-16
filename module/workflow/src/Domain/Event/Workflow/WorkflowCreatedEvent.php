<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Event\Workflow;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Workflow\Domain\Entity\WorkflowId;
use Ergonode\Workflow\Domain\ValueObject\Status;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

/**
 */
class WorkflowCreatedEvent implements DomainEventInterface
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
     * @var Status[]
     *
     * @JMS\Type("array<string, Ergonode\Workflow\Domain\ValueObject\Status>")
     */
    private $statuses;

    /**
     * @param WorkflowId $id
     * @param string     $code
     * @param Status[]   $statuses
     */
    public function __construct(WorkflowId $id, string $code, array $statuses = [])
    {
        Assert::allIsInstanceOf($statuses, Status::class);

        $this->id = $id;
        $this->code = $code;
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
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return Status[]
     */
    public function getStatuses(): array
    {
        return $this->statuses;
    }
}
