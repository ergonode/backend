<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Persistence\Dbal\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\Bus\EventBusInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventStoreInterface;
use Ergonode\EventSourcing\Infrastructure\Envelope\DomainEventEnvelope;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Entity\SimpleProduct;
use Ergonode\Product\Domain\Event\ProductCreatedEvent;
use Ergonode\Product\Domain\Event\ProductDeletedEvent;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

/**
 */
class DbalProductRepository implements ProductRepositoryInterface
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
     *
     * @throws \ReflectionException
     */
    public function load(ProductId $id): ?AbstractProduct
    {
        $eventStream = $this->eventStore->load($id);

        $result = null;
        if (0 !== count($eventStream)) {
            /** @var DomainEventEnvelope $envelope */
            $envelope = $eventStream->getIterator()->current();
            /** @var ProductCreatedEvent $event */
            $event = $envelope->getEvent();

            $class = new \ReflectionClass($event->getClass());
            /** @var AbstractAggregateRoot $aggregate */
            $aggregate = $class->newInstanceWithoutConstructor();
            if (!$aggregate instanceof AbstractProduct) {
                throw new \LogicException(sprintf('Impossible to initialize "%s"', SimpleProduct::class));
            }

            $aggregate->initialize($eventStream);

            $result = $aggregate;
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function save(AbstractProduct $aggregateRoot): void
    {
        $events = $aggregateRoot->popEvents();

        $this->eventStore->append($aggregateRoot->getId(), $events);
        foreach ($events as $envelope) {
            $this->eventBus->dispatch($envelope->getEvent());
        }
    }

    /**
     * @param ProductId $id
     *
     * @return bool
     */
    public function exists(ProductId $id): bool
    {
        return $this->eventStore->load($id)->count() > 0;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function delete(AbstractProduct $aggregateRoot): void
    {
        $aggregateRoot->apply(new ProductDeletedEvent($aggregateRoot->getId()));
        $this->save($aggregateRoot);

        $this->eventStore->delete($aggregateRoot->getId());
    }
}
