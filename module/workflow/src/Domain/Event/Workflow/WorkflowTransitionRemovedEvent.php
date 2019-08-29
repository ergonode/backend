<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Event\Workflow;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Workflow\Domain\Entity\StatusId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class WorkflowTransitionRemovedEvent implements DomainEventInterface
{
    /**
     * @var StatusId
     *
     * @JMS\Type("Ergonode\Workflow\Domain\Entity\StatusId")
     */
    private $source;

    /**
     * @var StatusId
     *
     * @JMS\Type("Ergonode\Workflow\Domain\Entity\StatusId")
     */
    private $destination;

    /**
     * @param StatusId $source
     * @param StatusId $destination
     */
    public function __construct(StatusId $source, StatusId $destination)
    {
        $this->source = $source;
        $this->destination = $destination;
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
