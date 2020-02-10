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
interface DomainEventStoreInterface
{
    /**
     * @param AggregateId $id
     * @param string      $table
     *
     * @return DomainEventStream
     */
    public function load(AggregateId $id, ?string $table = null): DomainEventStream;

    /**
     * @param AggregateId       $id
     * @param DomainEventStream $stream
     * @param string            $table
     */
    public function append(AggregateId $id, DomainEventStream $stream, ?string $table = null): void;

    /**
     * @param AggregateId $id
     * @param string|null $table
     */
    public function delete(AggregateId $id, ?string $table = null): void;
}
