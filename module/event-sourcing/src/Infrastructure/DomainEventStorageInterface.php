<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Infrastructure;

use Ergonode\EventSourcing\Infrastructure\Stream\DomainEventStream;
use Ergonode\SharedKernel\Domain\AggregateId;

/**
 */
interface DomainEventStorageInterface
{
    /**
     * @param AggregateId $id
     * @param int         $sequence
     *
     * @param string|null $name
     *
     * @return array
     */
    public function load(AggregateId $id, int $sequence = 0, string $name = null): array;

    /**
     * @param AggregateId       $id
     * @param DomainEventStream $stream
     * @param string|null       $name
     */
    public function append(AggregateId $id, DomainEventStream $stream, string $name = null): void;

    /**
     * @param AggregateId $id
     * @param string|null $name
     */
    public function delete(AggregateId $id, string $name = null): void;
}
