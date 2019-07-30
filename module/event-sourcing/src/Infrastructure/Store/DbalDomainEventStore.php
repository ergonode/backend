<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
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
     * @var string|null
     */
    private $userId;

    /**
     * @var AdapterInterface
     */
    private $cache;

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
        $this->connection = $connection;
        $this->serializer = $serializer;
        $this->domainEventFactory = $domainEventFactory;
        if ($tokenStorage->getToken()) {
            $this->userId = $tokenStorage->getToken()->getUser()->getId()->toString();
        }
        $this->cache = $cache;
    }

    /**
     * @param AbstractId $id
     *
     * @param string     $table
     *
     * @return DomainEventStream
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
     * @param AbstractId        $id
     * @param DomainEventStream $stream
     *
     * @param string            $table
     *
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Throwable
     */
    public function append(AbstractId $id, DomainEventStream $stream, ?string $table = null): void
    {
        $table = $table ?: self::TABLE;
        $this->connection->beginTransaction();
        try {
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
                        'recorded_by' => $this->userId ?: null,
                    ]
                );
            }
            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();
            throw $exception;
        }
    }
}
