<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Infrastructure\Storage;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Ergonode\EventSourcing\Infrastructure\DomainEventFactoryInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventStorageInterface;
use Ergonode\EventSourcing\Infrastructure\Provider\DomainEventProviderInterface;
use Ergonode\EventSourcing\Infrastructure\Stream\DomainEventStream;
use Ergonode\SharedKernel\Domain\AggregateId;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 */
class DbalDomainEventStorage implements DomainEventStorageInterface
{
    private const TABLE = 'event_store';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @var DomainEventFactoryInterface
     */
    private DomainEventFactoryInterface $domainEventFactory;

    /**
     * @var TokenStorageInterface
     */
    private TokenStorageInterface $tokenStorage;

    /**
     * @var DomainEventProviderInterface
     */
    private DomainEventProviderInterface $domainEventProvider;

    /**
     * @param Connection                   $connection
     * @param SerializerInterface          $serializer
     * @param DomainEventFactoryInterface  $domainEventFactory
     * @param TokenStorageInterface        $tokenStorage
     * @param DomainEventProviderInterface $domainEventProvider
     */
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
            ->select('es.id, es.aggregate_id, es.sequence, es.payload, es.recorded_by, es.recorded_at')
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
     * @param AggregateId       $id
     * @param DomainEventStream $stream
     *
     * @param string|null       $name
     *
     * @throws \Throwable
     */
    public function append(AggregateId $id, DomainEventStream $stream, string $name = null): void
    {
        $this->connection->transactional(function () use ($id, $stream, $name) {
            $table = $name ?: self::TABLE;
            $token = $this->tokenStorage->getToken();
            $userId = $token ? $token->getUser()->getId()->getValue() : null;
            foreach ($stream as $envelope) {
                $payload = $this->serializer->serialize($envelope->getEvent(), 'json');
                $this->connection->insert(
                    $table,
                    [
                        'aggregate_id' => $id->getValue(),
                        'sequence' => $envelope->getSequence(),
                        'event_id' => $this->domainEventProvider->provideEventId($envelope->getType()),
                        'payload' => $payload,
                        'recorded_at' => $envelope->getRecordedAt(),
                        'recorded_by' => $userId,
                    ],
                    [
                        'recorded_at' => Types::DATETIMETZ_MUTABLE,
                    ],
                );
            }
        });
    }

    /**
     * @param AggregateId $id
     * @param string|null $name
     *
     * @throws \Throwable
     */
    public function delete(AggregateId $id, ?string $name = null): void
    {
        $table = $name ?: self::TABLE;
        $historyTable = sprintf('%s_history', $table);

        $this->connection->transactional(function () use ($id, $table, $historyTable) {
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
