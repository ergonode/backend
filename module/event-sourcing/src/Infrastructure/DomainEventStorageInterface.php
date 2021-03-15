<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Infrastructure;

use Ergonode\EventSourcing\Infrastructure\Stream\DomainEventStream;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\EventSourcing\Infrastructure\Envelope\DomainEventEnvelope;

interface DomainEventStorageInterface
{
    /**
     * @return DomainEventEnvelope[]
     */
    public function load(AggregateId $id, int $sequence = 0, string $name = null): array;

    /**
     * Returns actual sequence for given aggregate
     */
    public function append(AggregateId $id, DomainEventStream $stream, string $name = null): int;

    public function delete(AggregateId $id, string $name = null): void;
}
