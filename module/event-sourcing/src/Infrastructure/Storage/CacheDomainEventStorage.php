<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Infrastructure\Storage;

use Ergonode\EventSourcing\Infrastructure\DomainEventStorageInterface;
use Ergonode\EventSourcing\Infrastructure\Stream\DomainEventStream;
use Ergonode\SharedKernel\Domain\AggregateId;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Psr\Cache\InvalidArgumentException;

class CacheDomainEventStorage implements DomainEventStorageInterface
{
    private const KEY = 'ev_%s';

    /**
     * @var DomainEventStorageInterface
     */
    private DomainEventStorageInterface $storage;

    /**
     * @var AdapterInterface
     */
    private AdapterInterface $cache;

    /**
     * @param DomainEventStorageInterface $storage
     * @param AdapterInterface            $cache
     */
    public function __construct(DomainEventStorageInterface $storage, AdapterInterface $cache)
    {
        $this->storage = $storage;
        $this->cache = $cache;
    }

    /**
     * @param AggregateId $id
     * @param int         $sequence
     *
     * @param string|null $name
     *
     * @return array
     *
     * @throws InvalidArgumentException
     */
    public function load(AggregateId $id, int $sequence = 0, string $name = null): array
    {
        $key = sprintf(self::KEY, $id->getValue());

        $item = $this->cache->getItem($key);
        $result = [];

        if ($item->isHit()) {
            $result = $item->get();
            $sequence = count($result);
        }

        $records = $this->storage->load($id, $sequence);

        if ($records) {
            $result = array_merge($result, $records);
            $item = $this->cache->getItem($key);
            $item->set($result);
            $this->cache->save($item);
        }

        return $result;
    }

    /**
     * @param AggregateId       $id
     * @param DomainEventStream $stream
     * @param string|null       $name
     */
    public function append(AggregateId $id, DomainEventStream $stream, string $name = null): void
    {
        $this->storage->append($id, $stream);
    }

    /**
     * @param AggregateId $id
     * @param string|null $table
     *
     * @param string|null $name
     *
     * @throws InvalidArgumentException
     */
    public function delete(AggregateId $id, ?string $table = null, string $name = null): void
    {
        $this->storage->delete($id);

        $key = sprintf(self::KEY, $id->getValue());
        $this->cache->deleteItem($key);
    }
}
