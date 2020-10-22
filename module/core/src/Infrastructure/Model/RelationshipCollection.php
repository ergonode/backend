<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Model;

use Ergonode\SharedKernel\Domain\AggregateId;

class RelationshipCollection implements \Iterator
{
    /**
     * @var array
     */
    private array $collection = [];

    public function add(AggregateId $id): void
    {
        $this->collection[] = $id;
    }

    public function get(int $key): AggregateId
    {
        return $this->collection[$key];
    }

    public function has(AggregateId $id): bool
    {
        return in_array($id, $this->collection, true);
    }

    public function isEmpty(): bool
    {
        return 0 === count($this->collection);
    }

    public function key(): int
    {
        return key($this->collection);
    }

    /**
     * @return AggregateId|bool
     */
    public function current()
    {
        return current($this->collection);
    }

    /**
     * @return AggregateId|bool
     */
    public function next()
    {
        return next($this->collection);
    }

    public function rewind(): void
    {
        reset($this->collection);
    }

    public function valid(): bool
    {
        return $this->current() instanceof AggregateId;
    }
}
