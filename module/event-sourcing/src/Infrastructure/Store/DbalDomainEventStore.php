<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Infrastructure\Store;

use Ergonode\EventSourcing\Infrastructure\DomainEventStoreInterface;
use Ergonode\EventSourcing\Infrastructure\Stream\DomainEventStream;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\EventSourcing\Infrastructure\DomainEventStorageInterface;

class DbalDomainEventStore implements DomainEventStoreInterface
{
    private DomainEventStorageInterface $storage;

    public function __construct(DomainEventStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function load(AggregateId $id, int $sequence = 0, string $name = null): DomainEventStream
    {
        return new DomainEventStream($this->storage->load($id, $sequence, $name));
    }

    public function append(AggregateId $id, DomainEventStream $stream, string $name = null): int
    {
        return $this->storage->append($id, $stream, $name);
    }

    public function delete(AggregateId $id, string $name = null): void
    {
        $this->storage->delete($id, $name);
    }
}
