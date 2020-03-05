<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Persistence\Repository;

use Ergonode\Editor\Domain\Entity\ProductDraft;
use Ergonode\SharedKernel\Domain\Aggregate\ProductDraftId;
use Ergonode\Editor\Domain\Event\ProductDraftApplied;
use Ergonode\Editor\Domain\Repository\ProductDraftRepositoryInterface;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\Bus\EventBusInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventStoreInterface;
use Ergonode\EventSourcing\Infrastructure\Stream\DomainEventStream;

/**
 */
class DbalProductDraftRepository implements ProductDraftRepositoryInterface
{
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
     * @param ProductDraftId $id
     * @param bool           $draft
     *
     * @return AbstractAggregateRoot
     *
     * @throws \ReflectionException
     */
    public function load(ProductDraftId $id, bool $draft = false): AbstractAggregateRoot
    {
        $eventStream = $this->eventStore->load($id);

        $class = new \ReflectionClass(ProductDraft::class);
        /** @var AbstractAggregateRoot $aggregate */
        $aggregate = $class->newInstanceWithoutConstructor();
        if (!$aggregate instanceof AbstractAggregateRoot) {
            throw new \LogicException(sprintf('Impossible to initialize "%s"', ProductDraft::class));
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

        $this->eventStore->append($aggregateRoot->getId(), $events);
        foreach ($events as $envelope) {
            $this->eventBus->dispatch($envelope->getEvent());
        }
    }

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function remove(AbstractAggregateRoot $aggregateRoot)
    {
        $events = new DomainEventStream([new ProductDraftApplied($aggregateRoot->getId())]);

        $this->eventStore->append($aggregateRoot->getId(), $events);
        foreach ($events as $envelope) {
            $this->eventBus->dispatch($envelope->getEvent());
        }
    }
}
