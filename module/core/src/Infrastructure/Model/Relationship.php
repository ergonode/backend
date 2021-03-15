<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Model;

use Webmozart\Assert\Assert;

class Relationship implements \Iterator, \Countable
{
    /**
     * @var RelationshipGroup[]
     */
    private array $collection;

    /**
     * @param RelationshipGroup[] $collection
     */
    public function __construct(array $collection)
    {
        Assert::minCount($collection, 1);
        Assert::allIsInstanceOf($collection, RelationshipGroup::class);

        $this->collection = $collection;
    }

    public function add(RelationshipGroup $group): void
    {
        $this->collection[] = $group;
    }

    public function get(string $key): RelationshipGroup
    {
        return $this->collection[$key];
    }

    public function has(string $key): bool
    {
        return in_array($key, $this->collection, true);
    }

    public function key(): string
    {
        return key($this->collection);
    }

    /**
     * @return RelationshipGroup|bool
     */
    public function current()
    {
        return current($this->collection);
    }

    /**
     * @return RelationshipGroup|bool
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
        return $this->current() instanceof RelationshipGroup;
    }

    public function count(): int
    {
        return count($this->collection);
    }
}
