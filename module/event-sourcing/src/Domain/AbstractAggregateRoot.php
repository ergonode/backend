<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Domain;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Envelope\DomainEventEnvelope;
use Ergonode\EventSourcing\Infrastructure\Stream\DomainEventStream;
use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\ExclusionPolicy("all")
 */
abstract class AbstractAggregateRoot
{
    /**
     * @var int
     */
    protected $sequence = 0;

    /**
     * @var array
     */
    protected $events = [];

    /**
     * @var \DateTime
     */
    protected $editedAt;

    /**
     * @return AbstractId
     */
    abstract public function getId(): AbstractId;

    /**
     * @return \DateTime
     */
    public function getEditedAt(): \DateTime
    {
        return $this->editedAt;
    }

    /**
     * @param DomainEventInterface $event
     *
     * @throws \Exception
     */
    public function apply(DomainEventInterface $event): void
    {
        $recordedAt = new \DateTime();
        $this->handle($event, $recordedAt);
        $this->sequence++;
        $this->events[] = new DomainEventEnvelope($this->getId(), $this->sequence, $event, $recordedAt);
    }

    /**
     * @param DomainEventStream $stream
     */
    public function initialize(DomainEventStream $stream): void
    {
        foreach ($stream as $event) {
            $this->sequence++;
            $this->handle($event->getPayload(), $event->getRecordedAt());
        }
    }

    /**
     * @return DomainEventStream
     */
    public function popEvents(): DomainEventStream
    {
        $result = new DomainEventStream($this->events);
        $this->events = [];

        return $result;
    }

    /**
     * @return int
     */
    public function getSequence(): int
    {
        return $this->sequence;
    }

    /**
     * @param DomainEventInterface $event
     * @param \DateTime            $recordedAt
     */
    private function handle(DomainEventInterface $event, \DateTime $recordedAt): void
    {
        $this->editedAt = $recordedAt;

        if (!$event instanceof AbstractDeleteEvent) {
            $classArray = explode('\\', get_class($event));
            $class = end($classArray);
            $method = sprintf('apply%s', $class);
            if (!method_exists($this, $method)) {
                throw new \RuntimeException(sprintf('Can\'t find method  %s for event in aggregate %s', $method, get_class($this)));
            }

            $this->$method($event);
        }
    }
}
