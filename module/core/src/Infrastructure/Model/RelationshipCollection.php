<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Model;

/**
 */
class RelationshipCollection
{
    /**
     * @var array
     */
    private $collection = [];

    /**
     * @param string $key
     * @param array  $value
     */
    public function set(string $key, array $value): void
    {
        $this->collection[$key] = $value;
    }

    /**
     * @param string $key
     *
     * @return array
     */
    public function get(string $key): array
    {
        return $this->collection[$key];
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->collection);
    }

    /**
     * @return array
     */
    public function getKeys(): array
    {
        return array_keys($this->collection);
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return !empty($this->collection);
    }
}
