<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Infrastructure\Snapshot;

use Doctrine\DBAL\Exception\InvalidArgumentException;
use Doctrine\DBAL\Types\Types;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;
use Ergonode\SharedKernel\Domain\AggregateId;
use Doctrine\DBAL\Connection;
use Ramsey\Uuid\Uuid;
use Doctrine\DBAL\DBALException;
use Ergonode\EventSourcing\Domain\AbstractEntity;

class DbalAggregateSnapshot implements AggregateSnapshotInterface
{
    private const TABLE = 'event_store_snapshot';
    private const SNAPSHOT_EVENTS = 10;

    private Connection $connection;

    private SerializerInterface $serializer;

    private int $snapshotEvents;

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
     * @throws \ReflectionException
     */
    public function load(AggregateId $id, string $class): ?AbstractAggregateRoot
    {
        $qb = $this->connection->createQueryBuilder();
        $record = $qb->select('sequence, payload')
            ->from(self::TABLE)
            ->where($qb->expr()->eq('aggregate_id', ':aggregateId'))
            ->setParameter(':aggregateId', $id->getValue())
            ->setMaxResults(1)
            ->orderBy('sequence', 'DESC')
            ->execute()
            ->fetch();

        if ($record) {
            /** @var AbstractAggregateRoot $aggregate */
            $aggregate = $this->serializer->deserialize($record['payload'], $class);

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
     * @throws DBALException
     */
    public function save(AbstractAggregateRoot $aggregate): void
    {
        if (0 === ($aggregate->getSequence() % $this->snapshotEvents)) {
            $payload = $this->serializer->serialize($aggregate);

            $this->connection->insert(
                self::TABLE,
                [
                    'aggregate_id' => $aggregate->getId()->getValue(),
                    'sequence' => $aggregate->getSequence(),
                    'payload' => $payload,
                    'recorded_by' => Uuid::uuid4()->toString(),
                    'recorded_at' => new \DateTime(),
                ],
                [
                    'recorded_at' => Types::DATETIMETZ_MUTABLE,
                ],
            );
        }
    }

    /**
     * @throws DBALException
     * @throws InvalidArgumentException
     */
    public function delete(AggregateId $id): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'aggregate_id' => $id->getValue(),
            ]
        );
    }
}
