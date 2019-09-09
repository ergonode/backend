<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Persistance\Dbal\Repository;

use Doctrine\DBAL\Connection;
use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\Stream\DomainEventStream;
use JMS\Serializer\SerializerInterface;

/**
 */
class DbalEventStoreRepository implements EventStoreRepositoryInterface
{
    private const TABLE = 'event_store';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param Connection          $connection
     * @param SerializerInterface $serializer
     */
    public function __construct(
        Connection $connection,
        SerializerInterface $serializer
    ) {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritDoc}
     */
    public function exists(AbstractId $id): bool
    {
        $eventStream = $this->load($id);

        return 0 !== count($eventStream);
    }

    /**
     * {@inheritDoc}
     */
    public function load(AbstractId $id, int $sequence = 0): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $records = $queryBuilder
            ->select('*')
            ->from(self::TABLE)
            ->where($queryBuilder->expr()->eq('aggregate_id', ':aggregateId'))
            ->andWhere($queryBuilder->expr()->gt('sequence', ':sequence'))
            ->setParameter('aggregateId', $id->getValue())
            ->setParameter('sequence', $sequence)
            ->orderBy('sequence', 'ASC')
            ->execute()
            ->fetchAll();

        return $records;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Throwable
     */
    public function append(AbstractId $id, DomainEventStream $stream, ?UserId $userId = null): void
    {
        $this->connection->transactional(function () use ($id, $stream, $userId) {
            foreach ($stream as $envelope) {
                $payload = $this->serializer->serialize($envelope->getEvent(), 'json');
                $this->connection->insert(
                    self::TABLE,
                    [
                        'aggregate_id' => $id->getValue(),
                        'sequence' => $envelope->getSequence(),
                        'event' => $envelope->getType(),
                        'payload' => $payload,
                        'recorded_at' => $envelope->getRecordedAt()->format('Y-m-d H:i:s'),
                        'recorded_by' => $userId->getValue(),
                    ]
                );
            }
        });
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Throwable
     */
    public function delete(AbstractId $id): void
    {
        $historyTable = sprintf('%s_history', self::TABLE);

        $this->connection->transactional(function () use ($id, $historyTable) {
            $queryBuilder = $this->connection->createQueryBuilder()
                ->from($historyTable)
                ->select('variant')
                ->where('aggregate_id = :id')
                ->orderBy('variant', 'DESC')
                ->setMaxResults(1)
                ->setParameter('id', $id->getValue());
            $version = $queryBuilder->execute()->fetchColumn();

            if (empty($version)) {
                $version = 1;
            }

            $this->connection->executeQuery(
                sprintf(
                    'INSERT INTO %s (aggregate_id, sequence, event, payload, recorded_by, recorded_at, variant) 
                    SELECT aggregate_id, sequence, event, payload, recorded_by, recorded_at, %d FROM %s WHERE aggregate_id = ?',
                    $historyTable,
                    $version,
                    self::TABLE
                ),
                [$id->getValue()]
            );

            $this->connection->delete(self::TABLE, ['aggregate_id' => $id->getValue()]);
        });
    }
}
