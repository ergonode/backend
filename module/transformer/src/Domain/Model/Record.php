<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Domain\Model;

use Ergonode\Value\Domain\ValueObject\ValueInterface;

/**
 */
class Record
{
    /**
     * @var array
     */
    private $columns;

    /**
     */
    public function __construct()
    {
        $this->columns = [];
    }

    /**
     * @param string         $collection
     * @param string         $name
     * @param ValueInterface $value
     */
    public function add(string $collection, string $name, ?ValueInterface $value = null): void
    {
        $this->columns[$collection][$name] = $value;
    }

    /**
     * @param string $column
     *
     * @return bool
     */
    public function has(string $column): bool
    {
        foreach ($this->columns as $collection) {
            if (array_key_exists($column, $collection)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $column
     *
     * @return ValueInterface|null
     */
    public function get(string $column): ?ValueInterface
    {
        foreach ($this->columns as $collection) {
            if (array_key_exists($column, $collection)) {
                return $collection[$column];
            }
        }

        throw new \InvalidArgumentException(\sprintf('Record haven\'t column %s', $column));
    }

    /**
     * @param string $collection
     *
     * @return ValueInterface[]
     */
    public function getColumns(string $collection): array
    {
        return $this->columns[$collection];
    }

    /**
     * @param string $collection
     *
     * @return bool
     */
    public function hasColumns(string $collection): bool
    {
        return isset($this->columns[$collection]);
    }
}
