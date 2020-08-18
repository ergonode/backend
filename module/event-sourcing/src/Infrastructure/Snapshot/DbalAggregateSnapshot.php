<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Infrastructure\Snapshot;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\AggregateId;
use Doctrine\DBAL\Connection;
use JMS\Serializer\SerializerInterface;
use Ramsey\Uuid\Uuid;
use Doctrine\DBAL\DBALException;
use Ergonode\EventSourcing\Domain\AbstractEntity;

/**
 */
class DbalAggregateSnapshot implements AggregateSnapshotInterface
{
    private const TABLE = 'event_store_snapshot';
    private const SNAPSHOT_EVENTS = 10;

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @var int
     */
    private int $snapshotEvents;

    /**
     * @param Connection          $connection
     * @param SerializerInterface $serializer
     * @param int                 $snapshotEvents
     */
    public function __construct(
        Connection $connection,
        SerializerInterface $serializer,
        int $snapshotEvents = self::SNAPSHOT_EVENTS
    ) {
        $this->connection = $connection;
        $this->serializer = $serializer;
        $this->snapshotEvents = $snapshotEvents;
    }

    /**
     * @param AggregateId $id
     * @param string      $class
     *
     * @return AbstractAggregateRoot
     *
     * @throws \ReflectionException
     */
    public function load(AggregateId $id, string $class): ?AbstractAggregateRoot
    {
        $qb = $this->connection->createQueryBuilder();
        $record = $qb->select('*')
            ->from(self::TABLE)
            ->where($qb->expr()->eq('aggregate_id', ':aggregateId'))
            ->setParameter(':aggregateId', $id->getValue())
            ->setMaxResults(1)
            ->orderBy('sequence', 'DESC')
            ->execute()
            ->fetch();

        if ($record) {
            /** @var AbstractAggregateRoot $aggregate */
            $aggregate = $this->serializer->deserialize($record['payload'], $class, 'json');

            $reflection = new \ReflectionClass($aggregate);
            $property = $reflection->getProperty('sequence');
            $property->setAccessible(true);
            $property->setValue($aggregate, $record['sequence']);

            $method = $reflection->getMethod('getEntities');
            $method->setAccessible(true);
            $entities = $method->invoke($aggregate);
            /** @var AbstractEntity $entity */
            foreach ($entities as $entity) {
                $entity->setAggregateRoot($aggregate);
            }

            return $aggregate;
        }

        return null;
    }

    /**
     * @param AbstractAggregateRoot $aggregate
     *
     * @throws DBALException
     */
    public function save(AbstractAggregateRoot $aggregate): void
    {
        if (0 === ($aggregate->getSequence() % $this->snapshotEvents)) {
            $payload = $this->serializer->serialize($aggregate, 'json');

            $this->connection->insert(
                self::TABLE,
                [
                    'aggregate_id' => $aggregate->getId()->getValue(),
                    'sequence' => $aggregate->getSequence(),
                    'payload' => $payload,
                    'recorded_by' => Uuid::uuid4()->toString(),
                    'recorded_at' => (new \DateTime())->format('Y-m-d H:i:s'),
                ]
            );
        }
    }
}
