<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Domain;

use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Envelope\DomainEventEnvelope;
use Ergonode\EventSourcing\Infrastructure\Stream\DomainEventStream;
use Ergonode\SharedKernel\Domain\AggregateId;
use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\ExclusionPolicy("all")
 */
abstract class AbstractAggregateRoot
{
    protected int $sequence = 0;

    /**
     * @var array
     */
    protected array $events = [];

    abstract public function getId(): AggregateId;

    /**
     * @throws \Exception
     */
    public function apply(DomainEventInterface $event): void
    {
        $recordedAt = new \DateTime();
        $this->handle($event, $recordedAt);
        $this->sequence++;
        $this->events[] = new DomainEventEnvelope($this->getId(), $this->sequence, $event, $recordedAt);
    }

    public function initialize(DomainEventStream $stream): void
    {
        foreach ($stream as $event) {
            $this->sequence++;
            $this->handle($event->getEvent(), $event->getRecordedAt());
        }
    }

    public function popEvents(): DomainEventStream
    {
        $result = new DomainEventStream($this->events);
        $this->events = [];

        return $result;
    }

    public function getSequence(): int
    {
        return $this->sequence;
    }

    /**
     * @return AbstractEntity[]
     */
    protected function getEntities(): array
    {
        return [];
    }

    private function handle(DomainEventInterface $event, \DateTime $recordedAt): void
    {
        if (!$event instanceof AbstractDeleteEvent) {
            $classArray = explode('\\', get_class($event));
            $class = end($classArray);
            $method = sprintf('apply%s', $class);
            if (method_exists($this, $method)) {
                $this->$method($event);
            }

            foreach ($this->getEntities() as $entity) {
                $entity->setAggregateRoot($this);
                $entity->handle($event, $recordedAt);
            }
        }
    }
}
