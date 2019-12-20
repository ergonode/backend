<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Persistence\Dbal\Repository;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\DomainEventDispatcherInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventStoreInterface;
use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;

/**
 */
class DbalMultimediaRepository implements MultimediaRepositoryInterface
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
     * @param MultimediaId $id
     *
     * @return Multimedia|null
     *
     * @throws \ReflectionException
     */
    public function load(MultimediaId $id): ?AbstractAggregateRoot
    {
        $eventStream = $this->eventStore->load($id);

        if (count($eventStream) > 0) {
            $class = new \ReflectionClass(Multimedia::class);
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
     * @param Multimedia $aggregateRoot
     */
    public function save(Multimedia $aggregateRoot): void
    {
        $events = $aggregateRoot->popEvents();
        $this->eventStore->append($aggregateRoot->getId(), $events);

        foreach ($events as $envelope) {
            $this->eventDispatcher->dispatch($envelope);
        }
    }

    /**
     * @param AbstractId $id
     *
     * @return bool
     */
    public function exists(AbstractId $id): bool
    {
        $eventStream = $this->eventStore->load($id);

        return count($eventStream) > 0;
    }

    /**
     * @param MultimediaId $id
     */
    public function remove(MultimediaId $id): void
    {
        $this->eventStore->delete($id);
    }
}
