<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Persistence\Repository;

use Ergonode\Editor\Domain\Entity\ProductDraft;
use Ergonode\Editor\Domain\Entity\ProductDraftId;
use Ergonode\Editor\Domain\Event\ProductDraftApplied;
use Ergonode\Editor\Domain\Repository\ProductDraftRepositoryInterface;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\DomainEventDispatcherInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventStoreInterface;
use Ergonode\EventSourcing\Infrastructure\Stream\DomainEventStream;

/**
 */
class DbalProductDraftRepository implements ProductDraftRepositoryInterface
{
    /**
     * @var DomainEventStoreInterface
     */
    private $eventStore;

    /**
     * @var DomainEventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param DomainEventStoreInterface      $eventStore
     * @param DomainEventDispatcherInterface $eventDispatcher
     */
    public function __construct(DomainEventStoreInterface $eventStore, DomainEventDispatcherInterface $eventDispatcher)
    {
        $this->eventStore = $eventStore;
        $this->eventDispatcher = $eventDispatcher;
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
            $this->eventDispatcher->dispatch($envelope);
        }
    }

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function remove(AbstractAggregateRoot $aggregateRoot)
    {
        $events = new DomainEventStream([new ProductDraftApplied()]);

        $this->eventStore->append($aggregateRoot->getId(), $events);
        foreach ($events as $envelope) {
            $this->eventDispatcher->dispatch($envelope);
        }
    }
}
