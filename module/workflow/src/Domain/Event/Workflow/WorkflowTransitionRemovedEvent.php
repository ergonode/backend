<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Event\Workflow;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use JMS\Serializer\Annotation as JMS;

/**
 */
class WorkflowTransitionRemovedEvent implements DomainEventInterface
{
    /**
     * @var WorkflowId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\WorkflowId")
     */
    private WorkflowId $id;

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
     * @param WorkflowId $id
     * @param StatusCode $source
     * @param StatusCode $destination
     */
    public function __construct(WorkflowId $id, StatusCode $source, StatusCode $destination)
    {
        $this->id = $id;
        $this->source = $source;
        $this->destination = $destination;
    }

    /**
     * @return WorkflowId
     */
    public function getAggregateId(): WorkflowId
    {
        return $this->id;
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
}
