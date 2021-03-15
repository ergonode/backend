<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Infrastructure;

use Ergonode\EventSourcing\Infrastructure\Stream\DomainEventStream;
use Ergonode\SharedKernel\Domain\AggregateId;

interface DomainEventStoreInterface
{
    public function load(AggregateId $id, int $sequence = 0, ?string $table = null): DomainEventStream;

    /**
     * Returns actual sequence for given aggregate
     */
    public function append(AggregateId $id, DomainEventStream $stream, ?string $table = null): int;

    public function delete(AggregateId $id, ?string $table = null): void;
}
