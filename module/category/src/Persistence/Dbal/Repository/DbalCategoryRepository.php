<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Persistence\Dbal\Repository;

use Ergonode\Category\Domain\Entity\Category;
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
    public function load(CategoryId $id): ?Category
    {
        $eventStream = $this->eventStore->load($id);
        if ($eventStream->count() > 0) {
            $class = new \ReflectionClass(Category::class);
            /** @var Category $aggregate */
            $aggregate = $class->newInstanceWithoutConstructor();
            if (!$aggregate instanceof Category) {
                throw new \LogicException(sprintf('Impossible to initialize "%s"', Category::class));
            }
            $aggregate->initialize($eventStream);

            return $aggregate;
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function save(Category $aggregateRoot): void
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
    public function delete(Category $category): void
    {
        $category->apply(new CategoryDeletedEvent($category->getId()));
        $this->save($category);

        $this->eventStore->delete($category->getId());
    }
}
