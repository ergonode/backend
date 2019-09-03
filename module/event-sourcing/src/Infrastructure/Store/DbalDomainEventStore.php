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
            $result =  $item->get();
            $sequence = count($result);
        } else {
            $result = [];
            $sequence = 0;
        }

        $qb = $this->connection->createQueryBuilder();

        $records = $qb
            ->select('*')
            ->from($table)
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
                        'event' => $envelope->getType(),
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
     */
    public function delete(AbstractId $id, ?string $table = null): void
    {
        $dataTable = $table ?? self::TABLE;
        $historyTable = sprintf('%s_history', $dataTable);

        $this->connection->transactional(function () use ($id, $dataTable, $historyTable) {
            $this->connection->executeQuery(
                sprintf('INSERT INTO %s SELECT * FROM %s WHERE aggregate_id = ?', $historyTable, $dataTable),
                [$id->getValue()]
            );

            $this->connection->delete($dataTable, ['aggregate_id' => $id->getValue()]);
        });
    }
}
