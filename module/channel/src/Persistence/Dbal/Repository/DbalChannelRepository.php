<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Persistence\Dbal\Repository;

use Ergonode\Channel\Domain\Entity\Channel;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\Channel\Domain\Event\ChannelCreatedEvent;
use Ergonode\Channel\Domain\Event\ChannelDeletedEvent;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\Bus\EventBusInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventStoreInterface;
use Ergonode\EventSourcing\Infrastructure\Envelope\DomainEventEnvelope;

/**
 */
class DbalChannelRepository implements ChannelRepositoryInterface
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
     * DbalChannelRepository constructor.
     *
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
    public function exists(ChannelId $id) : bool
    {
        return $this->eventStore->load($id)->count() > 0;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \ReflectionException
     */
    public function load(ChannelId $id): ?AbstractAggregateRoot
    {
        $eventStream = $this->eventStore->load($id);
        if ($eventStream->count() > 0) {
            $class = new \ReflectionClass(Channel::class);
            /** @var AbstractAggregateRoot $aggregate */
            $aggregate = $class->newInstanceWithoutConstructor();
            if (!$aggregate instanceof AbstractAggregateRoot) {
                throw new \LogicException(sprintf('Impossible to initialize "%s"', Channel::class));
            }
            $aggregate->initialize($eventStream);

            return $aggregate;
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function save(Channel $aggregateRoot): void
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
    public function delete(Channel $aggregateRoot): void
    {
        $aggregateRoot->apply(new ChannelDeletedEvent($aggregateRoot->getId()));
        $this->save($aggregateRoot);

        $this->eventStore->delete($aggregateRoot->getId());
    }
}
