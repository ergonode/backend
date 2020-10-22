<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Infrastructure\Manager;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\Bus\EventBusInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventStoreInterface;
use Ergonode\EventSourcing\Infrastructure\Snapshot\AggregateSnapshotInterface;
use Ergonode\SharedKernel\Domain\AggregateId;

class EventStoreManager
{
    private AggregateBuilderInterface $builder;

    private DomainEventStoreInterface $eventStore;

    private EventBusInterface $eventBus;

    private AggregateSnapshotInterface $snapshot;

    private Connection $connection;

    public function __construct(
        AggregateBuilderInterface $builder,
        DomainEventStoreInterface $eventStore,
        EventBusInterface $eventBus,
        AggregateSnapshotInterface $snapshot,
        Connection $connection
    ) {
        $this->builder = $builder;
        $this->eventStore = $eventStore;
        $this->eventBus = $eventBus;
        $this->snapshot = $snapshot;
        $this->connection = $connection;
    }

    /**
     * @throws \ReflectionException
     */
    public function load(AggregateId $id): ?AbstractAggregateRoot
    {
        $class = $this->findClass($id);
        if ($class) {
            $aggregate = $this->builder->build($id, $class);

            if ($aggregate) {
                $eventStream = $this->eventStore->load($id, $aggregate->getSequence());
                $aggregate->initialize($eventStream);
            }

            return $aggregate;
        }

        return null;
    }

    /**
     * @throws DBALException
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void
    {
        $events = $aggregateRoot->popEvents();

        if ($events->count() > 0) {
            if (($events->count() - $aggregateRoot->getSequence()) === 0) {
                $this->addClass($aggregateRoot);
            }
            $this->eventStore->append($aggregateRoot->getId(), $events);
            foreach ($events as $envelope) {
                $this->eventBus->dispatch($envelope->getEvent());
            }
            $this->snapshot->save($aggregateRoot);
        }
    }

    public function exists(AggregateId $id): bool
    {
        return null !== $this->findClass($id);
    }

    /**
     * @throws DBALException
     * @throws InvalidArgumentException
     */
    public function delete(AbstractAggregateRoot $aggregateRoot): void
    {
        $this->eventStore->delete($aggregateRoot->getId());
        $this->deleteClass($aggregateRoot);
        $this->snapshot->delete($aggregateRoot->getId());
    }

    private function findClass(AggregateId $id): ?string
    {
        $qb = $this->connection->createQueryBuilder();
        $result = $qb->select('class')
            ->from('event_store_class')
            ->where($qb->expr()->eq('aggregate_id', ':aggregateId'))
            ->setParameter(':aggregateId', $id->getValue())
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        if ($result) {
            return $result;
        }

        return null;
    }

    /**
     * @throws DBALException
     */
    private function addClass(AbstractAggregateRoot $aggregate): void
    {
        $this->connection->insert(
            'event_store_class',
            [
                'aggregate_id' => $aggregate->getId()->getValue(),
                'class' => get_class($aggregate),
            ]
        );
    }

    /**
     * @throws DBALException
     * @throws InvalidArgumentException
     */
    private function deleteClass(AbstractAggregateRoot $aggregate): void
    {
        $this->connection->delete(
            'event_store_class',
            [
                'aggregate_id' => $aggregate->getId()->getValue(),
            ]
        );
    }
}
