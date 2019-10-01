<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Event\Workflow;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use Ergonode\Workflow\Domain\ValueObject\Transition;
use JMS\Serializer\Annotation as JMS;

/**
 */
class WorkflowTransitionChangedEvent implements DomainEventInterface
{
    /**
     * @var StatusCode
     *
     * @JMS\Type("Ergonode\Workflow\Domain\ValueObject\StatusCode")
     */
    private $source;

    /**
     * @var StatusCode
     *
     * @JMS\Type("Ergonode\Workflow\Domain\ValueObject\StatusCode")
     */
    private $destination;

    /**
     * @var Transition
     *
     * @JMS\Type("Ergonode\Workflow\Domain\ValueObject\Transition")
     */
    private $from;

    /**
     * @var Transition
     *
     * @JMS\Type("Ergonode\Workflow\Domain\ValueObject\Transition")
     */
    private $to;

    /**
     * @param StatusCode $source
     * @param StatusCode $destination
     * @param Transition $from
     * @param Transition $to
     */
    public function __construct(StatusCode $source, StatusCode $destination, Transition $from, Transition $to)
    {
        $this->source = $source;
        $this->destination = $destination;
        $this->from = $from;
        $this->to = $to;
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
     * @return Transition
     */
    public function getFrom(): Transition
    {
        return $this->from;
    }

    /**
     * @return Transition
     */
    public function getTo(): Transition
    {
        return $this->to;
    }
}
