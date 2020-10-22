<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Infrastructure\Store;

use Ergonode\EventSourcing\Infrastructure\DomainEventStoreInterface;
use Ergonode\EventSourcing\Infrastructure\Stream\DomainEventStream;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\EventSourcing\Infrastructure\DomainEventStorageInterface;

class DbalDomainEventStore implements DomainEventStoreInterface
{
    /**
     * @var DomainEventStorageInterface
     */
    private DomainEventStorageInterface $storage;

    /**
     * @param DomainEventStorageInterface $storage
     */
    public function __construct(DomainEventStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param AggregateId $id
     * @param int         $sequence
     * @param string|null $name
     *
     * @return DomainEventStream
     */
    public function load(AggregateId $id, int $sequence = 0, string $name = null): DomainEventStream
    {
        return new DomainEventStream($this->storage->load($id, $sequence, $name));
    }

    /**
     * @param AggregateId       $id
     * @param DomainEventStream $stream
     * @param string|null       $name
     */
    public function append(AggregateId $id, DomainEventStream $stream, string $name = null): void
    {
        $this->storage->append($id, $stream, $name);
    }

    /**
     * @param AggregateId $id
     * @param string|null $name
     */
    public function delete(AggregateId $id, string $name = null): void
    {
        $this->storage->delete($id, $name);
    }
}
