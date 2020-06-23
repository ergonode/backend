<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Persistence\Dbal\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\Bus\EventBusInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventStoreInterface;
use Ergonode\Multimedia\Domain\Entity\Avatar;
use Ergonode\Multimedia\Domain\Event\AvatarDeletedEvent;
use Ergonode\Multimedia\Domain\Repository\AvatarRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AvatarId;

/**
 */
class DbalAvatarRepository implements AvatarRepositoryInterface
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
    public function __construct(
        DomainEventStoreInterface $eventStore,
        EventBusInterface $eventBus
    ) {
        $this->eventStore = $eventStore;
        $this->eventBus = $eventBus;
    }

    /**
     * @param AvatarId $id
     *
     * @return Avatar|null
     *
     * @throws \ReflectionException
     */
    public function load(AvatarId $id): ?AbstractAggregateRoot
    {
        $eventStream = $this->eventStore->load($id);

        if (count($eventStream) > 0) {
            $class = new \ReflectionClass(Avatar::class);
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
     * @param Avatar $aggregateRoot
     */
    public function save(Avatar $aggregateRoot): void
    {
        $events = $aggregateRoot->popEvents();
        $this->eventStore->append($aggregateRoot->getId(), $events);

        foreach ($events as $envelope) {
            $this->eventBus->dispatch($envelope->getEvent());
        }
    }

    /**
     * @param AvatarId $id
     *
     * @return bool
     */
    public function exists(AvatarId $id): bool
    {
        $eventStream = $this->eventStore->load($id);

        return count($eventStream) > 0;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function delete(Avatar $avatar): void
    {
        $avatar->apply(new AvatarDeletedEvent($avatar->getId()));
        $this->save($avatar);

        $this->eventStore->delete($avatar->getId());
    }
}
