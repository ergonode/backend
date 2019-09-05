<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Domain\Factory;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\Stream\DomainEventStream;

/**
 */
class EventStreamAggregateRootFactory
{
    /**
     * @param DomainEventStream $eventStream
     * @param string            $entityClass
     *
     * @return AbstractAggregateRoot
     *
     * @throws \ReflectionException
     */
    public function create(DomainEventStream $eventStream, string $entityClass): AbstractAggregateRoot
    {
        $class = new \ReflectionClass($entityClass);
        /** @var AbstractAggregateRoot $aggregate */
        $aggregate = $class->newInstanceWithoutConstructor();
        $aggregate->initialize($eventStream);

        return $aggregate;
    }
}
