<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Account\Persistence\Dbal\Repository;

use Ergonode\Account\Domain\Entity\Role;
use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\Account\Domain\Event\Role\RoleRemovedEvent;
use Ergonode\Account\Domain\Repository\RoleRepositoryInterface;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\DomainEventDispatcherInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventStoreInterface;

/**
 */
class DbalRoleRepository implements RoleRepositoryInterface
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
     * @param RoleId $id
     *
     * @return Role|null
     *
     * @throws \ReflectionException
     */
    public function load(RoleId $id): ?AbstractAggregateRoot
    {
        $eventStream = $this->eventStore->load($id);

        if (count($eventStream) > 0) {
            $class = new \ReflectionClass(Role::class);
            /** @var AbstractAggregateRoot|Role $aggregate */
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
        $aggregateRoot->apply(new RoleRemovedEvent());

        $this->save($aggregateRoot);

        $this->eventStore->delete($aggregateRoot->getId());
    }
}
