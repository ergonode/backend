<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Event\Workflow;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

class WorkflowTransitionRemovedEvent implements DomainEventInterface
{
    /**
     * @var WorkflowId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\WorkflowId")
     */
    private WorkflowId $id;

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
     * @param WorkflowId $id
     * @param StatusId   $source
     * @param StatusId   $destination
     */
    public function __construct(WorkflowId $id, StatusId $source, StatusId $destination)
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
}
