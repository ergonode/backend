<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Infrastructure\Manager;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\EventSourcing\Infrastructure\DomainEventStoreInterface;
use Ergonode\EventSourcing\Infrastructure\Bus\EventBusInterface;
use Ergonode\EventSourcing\Infrastructure\Snapshot\AggregateSnapshot;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\InvalidArgumentException;

/**
 */
class ESManager
{

    /**
     * @var AggregateBuilderInterface
     */
    private AggregateBuilderInterface $builder;

    /**
     * @var DomainEventStoreInterface
     */
    private DomainEventStoreInterface $eventStore;

    /**
     * @var EventBusInterface
     */
    private EventBusInterface $eventBus;

    /**
     * @var AggregateSnapshot
     */
    private AggregateSnapshot $snapshot;

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @param AggregateBuilderInterface $builder
     * @param DomainEventStoreInterface $eventStore
     * @param EventBusInterface         $eventBus
     * @param AggregateSnapshot         $snapshot
     * @param Connection                $connection
     */
    public function __construct(
        AggregateBuilderInterface $builder,
        DomainEventStoreInterface $eventStore,
        EventBusInterface $eventBus,
        AggregateSnapshot $snapshot,
        Connection $connection
    ) {
        $this->builder = $builder;
        $this->eventStore = $eventStore;
        $this->eventBus = $eventBus;
        $this->snapshot = $snapshot;
        $this->connection = $connection;
    }

    /**
     * @param AggregateId $id
     *
     * @return AbstractAggregateRoot|null
     *
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
     * @param AbstractAggregateRoot $aggregateRoot
     *
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

    /**
     * @param AggregateId $id
     *
     * @return bool
     */
    public function exists(AggregateId $id): bool
    {
        return null !== $this->findClass($id);
    }

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     *
     * @throws DBALException
     * @throws InvalidArgumentException
     */
    public function delete(AbstractAggregateRoot $aggregateRoot): void
    {
        $this->eventStore->delete($aggregateRoot->getId());
        $this->deleteClass($aggregateRoot);
    }

    /**
     * @param AggregateId $id
     *
     * @return string|null
     */
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
     * @param AbstractAggregateRoot $aggregate
     *
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
     * @param AbstractAggregateRoot $aggregate
     *
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
