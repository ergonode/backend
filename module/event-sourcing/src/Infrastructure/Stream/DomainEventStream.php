<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Infrastructure\Stream;

use Ergonode\EventSourcing\Infrastructure\Envelope\DomainEventEnvelope;

class DomainEventStream implements \IteratorAggregate, \Countable
{
    /**
     * @var DomainEventEnvelope[]
     */
    private array $events;

    /**
     * @param DomainEventEnvelope[] $events
     */
    public function __construct(array $events)
    {
        $this->events = [];
        foreach ($events as $event) {
            $this->addEvent($event);
        }
    }

    /**
     * @return \ArrayIterator|DomainEventEnvelope[]
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->events);
    }

    public function count(): int
    {
        return count($this->events);
    }

    private function addEvent(DomainEventEnvelope $event): void
    {
        $this->events[] = $event;
    }
}
