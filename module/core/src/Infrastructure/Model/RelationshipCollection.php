<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Model;

use Ergonode\SharedKernel\Domain\AggregateId;

/**
 */
class RelationshipCollection implements \Iterator
{
    /**
     * @var array
     */
    private array $collection = [];

    /**
     * @param AggregateId $id
     */
    public function add(AggregateId $id): void
    {
        $this->collection[] = $id;
    }

    /**
     * @param int $key
     *
     * @return AggregateId
     */
    public function get(int $key): AggregateId
    {
        return $this->collection[$key];
    }

    /**
     * @param AggregateId $id
     *
     * @return bool
     */
    public function has(AggregateId $id): bool
    {
        return in_array($id, $this->collection, true);
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return 0 === count($this->collection);
    }

    /**
     * @return int
     */
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

    /**
     */
    public function rewind(): void
    {
        reset($this->collection);
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return $this->current() instanceof AggregateId;
    }
}
