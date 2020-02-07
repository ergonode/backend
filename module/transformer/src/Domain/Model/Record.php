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
    private array $elements;

    /**
     */
    public function __construct()
    {
        $this->elements = [];
    }

    /**
     * @param string              $name
     * @param ValueInterface|null $value
     */
    public function set(string $name, ?ValueInterface $value = null): void
    {
        $this->elements[$name] = $value;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        if (array_key_exists($name, $this->elements)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $name
     *
     * @return ValueInterface|null
     */
    public function get(string $name): ?ValueInterface
    {
        if (array_key_exists($name, $this->elements)) {
            return $this->elements[$name];
        }

        throw new \InvalidArgumentException(\sprintf('Record haven\'t field %s', $name));
    }
}
