<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Model;

use Ergonode\Core\Domain\Entity\AbstractId;

/**
 */
class RelationshipCollection implements \Iterator
{
    /**
     * @var array
     */
    private $collection = [];

    /**
     * @param AbstractId $id
     */
    public function add(AbstractId $id): void
    {
        $this->collection[] = $id;
    }

    /**
     * @param int $key
     *
     * @return AbstractId
     */
    public function get(int $key): AbstractId
    {
        return $this->collection[$key];
    }

    /**
     * @param AbstractId $id
     *
     * @return bool
     */
    public function has(AbstractId $id): bool
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
     * @return AbstractId|bool
     */
    public function current()
    {
        return current($this->collection);
    }

    /**
     * @return AbstractId|bool
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
        return $this->current() instanceof AbstractId;
    }
}
