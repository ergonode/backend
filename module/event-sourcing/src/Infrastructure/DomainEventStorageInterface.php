<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Infrastructure;

use Ergonode\EventSourcing\Infrastructure\Stream\DomainEventStream;
use Ergonode\SharedKernel\Domain\AggregateId;

interface DomainEventStorageInterface
{
    /**
     * @return array
     */
    public function load(AggregateId $id, int $sequence = 0, string $name = null): array;

    public function append(AggregateId $id, DomainEventStream $stream, string $name = null): void;

    public function delete(AggregateId $id, string $name = null): void;
}
