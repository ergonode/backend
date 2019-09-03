<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Persistence\Dbal\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\DomainEventDispatcherInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventStoreInterface;
use Ergonode\Workflow\Domain\Entity\Status;
use Ergonode\Workflow\Domain\Entity\StatusId;
use Ergonode\Workflow\Domain\Event\Status\StatusDeletedEvent;
use Ergonode\Workflow\Domain\Repository\StatusRepositoryInterface;

/**
 */
class DbalStatusRepository implements StatusRepositoryInterface
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
    public function __construct(
        DomainEventStoreInterface $eventStore,
        DomainEventDispatcherInterface $eventDispatcher
    ) {
        $this->eventStore = $eventStore;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param StatusId $id
     *
     * @return Status|null
     *
     * @throws \ReflectionException
     */
    public function load(StatusId $id): ?AbstractAggregateRoot
    {
        $eventStream = $this->eventStore->load($id);
        if (count($eventStream) > 0) {
            $class = new \ReflectionClass(Status::class);
            /** @var Status $aggregate */
            $aggregate = $class->newInstanceWithoutConstructor();
            if (!$aggregate instanceof AbstractAggregateRoot) {
                throw new \LogicException(sprintf('Impossible to initialize "%s"', Status::class));
            }

            $aggregate->initialize($eventStream);

            return $aggregate;
        }

        return null;
    }

    /**
     * @param StatusId $id
     *
     * @return bool
     */
    public function exists(StatusId $id) : bool
    {
        return $this->eventStore->load($id)->count() > 0;
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
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function delete(AbstractAggregateRoot $aggregateRoot): void
    {
        $aggregateRoot->apply(new StatusDeletedEvent());
        $this->save($aggregateRoot);

        $this->eventStore->delete($aggregateRoot->getId());
    }
}
