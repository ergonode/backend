<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Infrastructure\Storage;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Ergonode\EventSourcing\Infrastructure\DomainEventFactoryInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventStorageInterface;
use Ergonode\EventSourcing\Infrastructure\Provider\DomainEventProviderInterface;
use Ergonode\EventSourcing\Infrastructure\Stream\DomainEventStream;
use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\SharedKernel\Domain\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DbalDomainEventStorage implements DomainEventStorageInterface
{
    private const TABLE = 'event_store';

    private Connection $connection;

    private SerializerInterface $serializer;

    private DomainEventFactoryInterface $domainEventFactory;

    private TokenStorageInterface $tokenStorage;

    private DomainEventProviderInterface $domainEventProvider;

    public function __construct(
        Connection $connection,
        SerializerInterface $serializer,
        DomainEventFactoryInterface $domainEventFactory,
        TokenStorageInterface $tokenStorage,
        DomainEventProviderInterface $domainEventProvider
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->connection = $connection;
        $this->serializer = $serializer;
        $this->domainEventFactory = $domainEventFactory;
        $this->domainEventProvider = $domainEventProvider;
    }

    /**
     * {@inheritDoc}
     */
    public function load(AggregateId $id, int $sequence = 0, string $name = null): array
    {
        $table = $name ?: self::TABLE;
        $qb = $this->connection->createQueryBuilder();

        $records = $qb
            ->select('es.aggregate_id, es.sequence, es.payload, es.recorded_at')
            ->addSelect('ese.event_class as event')
            ->from($table, 'es')
            ->join('es', 'event_store_event', 'ese', 'es.event_id = ese.id')
            ->where($qb->expr()->eq('aggregate_id', ':aggregateId'))
            ->andWhere($qb->expr()->gt('sequence', ':sequence'))
            ->setParameter('aggregateId', $id->getValue())
            ->setParameter('sequence', $sequence)
            ->addOrderBy('sequence', 'ASC')
            ->execute()
            ->fetchAll();

        return $this->domainEventFactory->create($id, $records);
    }

    /**
     * @throws \Throwable
     */
    public function append(AggregateId $id, DomainEventStream $stream, string $name = null): int
    {
        if (0 === $stream->count()) {
            throw new \DomainException('Cannot append empty events stream.');
        }

        return $this->connection->transactional(function () use ($id, $stream, $name): int {
            $table = $name ?: self::TABLE;
            $token = $this->tokenStorage->getToken();
            $userId = null;
            if ($token) {
                $user = $token->getUser();
                if ($user instanceof UserInterface) {
                    $userId = $user->getId()->getValue();
                }
            }
            $sql = "
                INSERT INTO $table (aggregate_id, sequence, event_id, payload, recorded_at, recorded_by)
                    VALUES (
                        :aggregateId,
                         COALESCE(
                            (
                                (SELECT sequence FROM $table WHERE aggregate_id = :aggregateId
                                    ORDER BY sequence DESC LIMIT 1
                                ) + 1
                            ),
                            1
                        ),
                        :eventId,
                        :payload,
                        :recorderAt,
                        :recordedBy
                    )
                    RETURNING sequence
                ";

            $stmts = [];
            foreach ($stream as $envelope) {
                $payload = $this->serializer->serialize($envelope->getEvent());
                $stmt = $this->connection->prepare($sql);

                $stmt->bindValue('aggregateId', $id->getValue());
                $stmt->bindValue('eventId', $this->domainEventProvider->provideEventId($envelope->getType()));
                $stmt->bindValue('payload', $payload);
                $stmt->bindValue('recorderAt', $envelope->getRecordedAt(), Types::DATETIMETZ_MUTABLE);
                $stmt->bindValue('recordedBy', $userId);

                $stmts[] = $stmt;
            }

            $this->connection->exec("LOCK TABLE $table IN EXCLUSIVE MODE");

            foreach ($stmts as $stmt) {
                $stmt->execute();
                $sequence = $stmt->fetchColumn();
            }

            return $sequence; /** @phpstan-ignore-line */
        });
    }

    /**
     * @throws \Throwable
     */
    public function delete(AggregateId $id, ?string $name = null): void
    {
        $table = $name ?: self::TABLE;
        $historyTable = sprintf('%s_history', $table);

        $this->connection->transactional(function () use ($id, $table, $historyTable): void {
            $queryBuilder = $this->connection->createQueryBuilder()
                ->from($historyTable)
                ->select('variant')
                ->where('aggregate_id = :id')
                ->orderBy('variant', 'DESC')
                ->setMaxResults(1)
                ->setParameter('id', $id->getValue());
            $version = $queryBuilder->execute()->fetchColumn();

            if (empty($version)) {
                $version = 0;
            }

            $this->connection->executeQuery(
                sprintf(
                    'INSERT INTO %s (aggregate_id, sequence, event_id, payload, recorded_by, recorded_at, variant) 
                    SELECT aggregate_id, sequence, event_id, payload, recorded_by, recorded_at, %d FROM %s WHERE 
                     aggregate_id = ?',
                    $historyTable,
                    $version + 1,
                    $table
                ),
                [$id->getValue()]
            );

            $this->connection->delete($table, ['aggregate_id' => $id->getValue()]);
        });
    }
}
