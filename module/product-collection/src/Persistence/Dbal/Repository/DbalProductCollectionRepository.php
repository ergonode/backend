<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Persistence\Dbal\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\Bus\EventBusInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventStoreInterface;
use Ergonode\ProductCollection\Domain\Entity\ProductCollection;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionDeletedEvent;
use Ergonode\ProductCollection\Domain\Repository\ProductCollectionRepositoryInterface;

/**
 */
class DbalProductCollectionRepository implements ProductCollectionRepositoryInterface
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
     * {@inheritDoc}
     */
    public function exists(ProductCollectionId $id): bool
    {
        return $this->eventStore->load($id)->count() > 0;
    }

    /**
     * @param ProductCollectionId $id
     *
     * @return AbstractAggregateRoot|null
     *
     * @throws \ReflectionException
     */
    public function load(ProductCollectionId $id): ?AbstractAggregateRoot
    {
        $eventStream = $this->eventStore->load($id);
        if (count($eventStream) > 0) {
            $class = new \ReflectionClass(ProductCollection::class);
            /** @var AbstractAggregateRoot $aggregate */
            $aggregate = $class->newInstanceWithoutConstructor();
            if (!$aggregate instanceof AbstractAggregateRoot) {
                throw new \LogicException(sprintf('Impossible to initialize "%s"', ProductCollection::class));
            }

            $aggregate->initialize($eventStream);

            return $aggregate;
        }

        return null;
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function delete(AbstractAggregateRoot $aggregateRoot): void
    {
        $aggregateRoot->apply(new ProductCollectionDeletedEvent($aggregateRoot->getId()));
        $this->save($aggregateRoot);

        $this->eventStore->delete($aggregateRoot->getId());
    }
}
