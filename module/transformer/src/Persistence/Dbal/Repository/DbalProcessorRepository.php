<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Persistence\Dbal\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\Bus\EventBusInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventStoreInterface;
use Ergonode\Transformer\Domain\Entity\Processor;
use Ergonode\Transformer\Domain\Entity\ProcessorId;
use Ergonode\Transformer\Domain\Repository\ProcessorRepositoryInterface;

/**
 */
class DbalProcessorRepository implements ProcessorRepositoryInterface
{
    private const TABLE = 'importer.event_store';

    /**
     * @var DomainEventStoreInterface
     */
    private DomainEventStoreInterface $eventStore;

    /**
     * @var EventBusInterface
     */
    private EventBusInterface $eventBus;

    /**
     * @param DomainEventStoreInterface $eventStore
     * @param EventBusInterface         $eventBus
     */
    public function __construct(DomainEventStoreInterface $eventStore, EventBusInterface $eventBus)
    {
        $this->eventStore = $eventStore;
        $this->eventBus = $eventBus;
    }

    /**
     * @param ProcessorId $id
     *
     * @return AbstractAggregateRoot
     *
     * @throws \ReflectionException
     */
    public function load(ProcessorId $id): AbstractAggregateRoot
    {
        $eventStream = $this->eventStore->load($id, self::TABLE);

        $class = new \ReflectionClass(Processor::class);
        /** @var AbstractAggregateRoot $aggregate */
        $aggregate = $class->newInstanceWithoutConstructor();
        if (!$aggregate instanceof AbstractAggregateRoot) {
            throw new \LogicException(sprintf('Impossible to initialize "%s"', Processor::class));
        }

        $aggregate->initialize($eventStream);

        return $aggregate;
    }

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void
    {
        $events = $aggregateRoot->popEvents();

        $this->eventStore->append($aggregateRoot->getId(), $events, self::TABLE);
        foreach ($events as $envelope) {
            $this->eventBus->dispatch($envelope->getEvent());
        }
    }
}
