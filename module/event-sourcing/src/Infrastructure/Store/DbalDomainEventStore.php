<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Infrastructure\Store;

use Doctrine\DBAL\Connection;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventFactoryInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventStoreInterface;
use Ergonode\EventSourcing\Infrastructure\Stream\DomainEventStream;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 */
class DbalDomainEventStore implements DomainEventStoreInterface
{
    private const TABLE = 'event_store';
    private const KEY = 'ev_%s';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var DomainEventFactoryInterface
     */
    private $domainEventFactory;

    /**
     * @var AdapterInterface
     */
    private $cache;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var array
     */
    private $events;

    /**
     * @param Connection                  $connection
     * @param SerializerInterface         $serializer
     * @param DomainEventFactoryInterface $domainEventFactory
     * @param TokenStorageInterface       $tokenStorage
     * @param AdapterInterface            $cache
     */
    public function __construct(
        Connection $connection,
        SerializerInterface $serializer,
        DomainEventFactoryInterface $domainEventFactory,
        TokenStorageInterface $tokenStorage,
        AdapterInterface $cache
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->connection = $connection;
        $this->serializer = $serializer;
        $this->domainEventFactory = $domainEventFactory;
        $this->cache = $cache;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function load(AbstractId $id, ?string $table = null): DomainEventStream
    {
        $table = $table ?: self::TABLE;

        $key = sprintf(self::KEY, $id->getValue());

        $item = $this->cache->getItem($key);
        if ($item->isHit()) {
            $result = $item->get();
            $sequence = count($result);
        } else {
            $result = [];
            $sequence = 0;
        }

        $qb = $this->connection->createQueryBuilder();

        $records = $qb
            ->select('es.id, es.aggregate_id, es.sequence, es.payload, es.recorded_by, es.recorded_at')
            ->addSelect('ese.event_class as event')
            ->from($table, 'es')
            ->join('es', 'event_store_event', 'ese', 'es.event = ese.id')
            ->where($qb->expr()->eq('aggregate_id', ':aggregateId'))
            ->andWhere($qb->expr()->gt('sequence', ':sequence'))
            ->setParameter('aggregateId', $id->getValue())
            ->setParameter('sequence', $sequence)
            ->addOrderBy('sequence', 'ASC')
            ->execute()
            ->fetchAll();

        if ($records) {
            $result = array_merge($result, $this->domainEventFactory->create($id, $records));
            $item = $this->cache->getItem($key);
            $item->set($result);
            $this->cache->save($item);
        }

        return new DomainEventStream($result);
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Throwable
     */
    public function append(AbstractId $id, DomainEventStream $stream, ?string $table = null): void
    {
        $table = $table ?: self::TABLE;

        $this->connection->transactional(function () use ($id, $stream, $table) {
            $userId = $this->tokenStorage->getToken() ? $this->tokenStorage->getToken()->getUser()->getId()->getValue() : null;
            foreach ($stream as $envelope) {
                $payload = $this->serializer->serialize($envelope->getEvent(), 'json');
                $this->connection->insert(
                    $table,
                    [
                        'aggregate_id' => $id->getValue(),
                        'sequence' => $envelope->getSequence(),
                        'event' => $this->fetchEventId($envelope->getType()),
                        'payload' => $payload,
                        'recorded_at' => $envelope->getRecordedAt()->format('Y-m-d H:i:s'),
                        'recorded_by' => $userId,
                    ]
                );
            }
        });
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Throwable
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function delete(AbstractId $id, ?string $table = null): void
    {
        $dataTable = $table ?? self::TABLE;
        $historyTable = sprintf('%s_history', $dataTable);

        $this->connection->transactional(function () use ($id, $dataTable, $historyTable) {
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
                    $dataTable
                ),
                [$id->getValue()]
            );

            $this->connection->delete($dataTable, ['aggregate_id' => $id->getValue()]);
        });

        $key = sprintf(self::KEY, $id->getValue());
        $this->cache->deleteItem($key);
    }

    /**
     * @param string $class
     *
     * @return string
     */
    private function fetchEventId(string $class): string
    {
        if (null === $this->events) {
            $queryBuilder = $this->connection->createQueryBuilder()
                ->from('event_store_event')
                ->select('event_class, id');
            $this->events = $queryBuilder->execute()->fetchAll(\PDO::FETCH_KEY_PAIR);
        }

        if (!array_key_exists($class, $this->events)) {
            throw new \RuntimeException(sprintf(
                'Event class "%s" not found. Check event definition in "event_store_event" table',
                $class
            ));
        }

        return $this->events[$class];
    }
}
