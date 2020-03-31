<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Repository;

use Ergonode\Attribute\Domain\Entity\AbstractOption;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\Bus\EventBusInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventStoreInterface;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Attribute\Domain\Entity\Option\SimpleOption;
use Ergonode\Attribute\Domain\Repository\OptionRepositoryInterface;
use Ergonode\Attribute\Domain\Event\Option\OptionRemovedEvent;

/**
 */
class DbalOptionRepository implements OptionRepositoryInterface
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
     * @param AggregateId $id
     *
     * @return AbstractAggregateRoot|AbstractOption
     *
     * @throws \ReflectionException
     */
    public function load(AggregateId $id): ?AbstractOption
    {
        $eventStream = $this->eventStore->load($id);

        if (\count($eventStream) > 0) {
            $class = new \ReflectionClass(SimpleOption::class);
            /** @var AbstractAggregateRoot $aggregate */
            $aggregate = $class->newInstanceWithoutConstructor();
            if (!$aggregate instanceof AbstractAggregateRoot) {
                throw new \LogicException(sprintf('Impossible to initialize "%s"', $class));
            }

            $aggregate->initialize($eventStream);

            return $aggregate;
        }

        return null;
    }

    /**
     * @param AbstractOption $aggregateRoot
     */
    public function save(AbstractOption $aggregateRoot): void
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
    public function delete(AbstractOption $aggregateRoot): void
    {
        $aggregateRoot->apply(new OptionRemovedEvent($aggregateRoot->getId()));
        $this->save($aggregateRoot);

        $this->eventStore->delete($aggregateRoot->getId());
    }
}
