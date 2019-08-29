<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Persistence\Dbal\Repository;

use Ergonode\CategoryTree\Domain\Entity\CategoryTree;
use Ergonode\CategoryTree\Domain\Entity\CategoryTreeId;
use Ergonode\CategoryTree\Domain\Repository\TreeRepositoryInterface;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\DomainEventDispatcherInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventStoreInterface;

/**
 */
class DbalTreeRepository implements TreeRepositoryInterface
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
     * @param CategoryTreeId $id
     *
     * @return CategoryTree|null
     *
     * @throws \ReflectionException
     */
    public function load(CategoryTreeId $id): ?AbstractAggregateRoot
    {
        $eventStream = $this->eventStore->load($id);

        if ($eventStream->count() > 0) {
            $class = new \ReflectionClass(CategoryTree::class);
            /** @var AbstractAggregateRoot $aggregate */
            $aggregate = $class->newInstanceWithoutConstructor();
            if (!$aggregate instanceof AbstractAggregateRoot) {
                throw new \LogicException(sprintf('Impossible to initialize "%s"', CategoryTree::class));
            }

            $aggregate->initialize($eventStream);

            return $aggregate;
        }

        return null;
    }

    /**
     * @param CategoryTreeId $id
     *
     * @return bool
     */
    public function exists(CategoryTreeId $id) : bool
    {
        $eventStream = $this->eventStore->load($id);

        return $eventStream->count() > 0;
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
}
