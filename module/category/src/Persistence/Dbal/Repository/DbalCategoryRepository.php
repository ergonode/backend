<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Persistence\Dbal\Repository;

use Ergonode\Category\Domain\Entity\AbstractCategory;
use Ergonode\Category\Domain\Event\CategoryCreatedEvent;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\Envelope\DomainEventEnvelope;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Category\Domain\Event\CategoryDeletedEvent;
use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\EventSourcing\Infrastructure\Bus\EventBusInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventStoreInterface;

/**
 */
class DbalCategoryRepository implements CategoryRepositoryInterface
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
    public function exists(CategoryId $id) : bool
    {
        return $this->eventStore->load($id)->count() > 0;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \ReflectionException
     */
    public function load(CategoryId $id): ?AbstractCategory
    {
        $eventStream = $this->eventStore->load($id);

        if ($eventStream->count() > 0) {
            /** @var DomainEventEnvelope $envelope */
            $envelope = $eventStream->getIterator()->current();
            /** @var CategoryCreatedEvent $event */
            $event = $envelope->getEvent();

            $class = new \ReflectionClass($event->getClass());
            /** @var AbstractAggregateRoot $aggregate */
            $aggregate = $class->newInstanceWithoutConstructor();
            if (!$aggregate instanceof AbstractCategory) {
                throw new \LogicException(sprintf('Impossible to initialize "%s"', $class));
            }
            $aggregate->initialize($eventStream);

            return $aggregate;
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function save(AbstractCategory $aggregateRoot): void
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
    public function delete(AbstractCategory $category): void
    {
        $category->apply(new CategoryDeletedEvent($category->getId()));
        $this->save($category);

        $this->eventStore->delete($category->getId());
    }
}
