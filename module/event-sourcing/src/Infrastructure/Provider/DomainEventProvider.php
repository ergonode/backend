<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Infrastructure\Provider;

use Doctrine\DBAL\Connection;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class DomainEventProvider implements DomainEventProviderInterface
{
    private Connection $connection;

    private AdapterInterface $cache;

    public function __construct(Connection $connection, AdapterInterface $cache)
    {
        $this->connection = $connection;
        $this->cache = $cache;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function provideEventId(string $eventClass): string
    {
        $cacheItem = $this->cache->getItem(sha1($eventClass));

        if (!$cacheItem->isHit()) {
            $class = $this->fetchFromDatabase($eventClass);
            $cacheItem->set($class);
        }

        return (string) $cacheItem->get();
    }

    /**
     * @throws \RuntimeException
     */
    private function fetchFromDatabase(string $eventClass): string
    {
        $queryBuilder = $this->connection->createQueryBuilder()
            ->from('event_store_event')
            ->select('id')
            ->where('event_class = :class')
            ->setParameter('class', $eventClass);
        $eventId = $queryBuilder->execute()->fetchColumn();

        if (empty($eventId)) {
            throw new \RuntimeException(sprintf(
                'Event class "%s" not found. Check event definition in "event_store_event" table',
                $eventClass
            ));
        }

        return (string) $eventId;
    }
}
