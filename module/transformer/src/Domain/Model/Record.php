<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Domain\Model;

use Ergonode\Value\Domain\ValueObject\ValueInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class Record
{
    /**
     * @var ValueInterface[]
     *
     * @JMS\Type(array<string, Ergonode\Value\Domain\ValueObject\ValueInterface>)
     */
    private array $elements;

    /**
     * @var ValueInterface[]
     *
     * @JMS\Type(array<string, Ergonode\Value\Domain\ValueObject\ValueInterface>)
     */
    private array $values;

    /**
     * @param array $elements
     */
    public function __construct(array $elements = [])
    {
        $this->elements = [];
        $this->values = [];
        foreach ($elements as $key => $element) {
            $this->set($key, $element);
        }
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
     * @param string              $code
     * @param ValueInterface|null $value
     */
    public function setValue(string $code, ?ValueInterface $value): void
    {
        $this->values[$code] = $value;
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
     * @return bool
     */
    public function hasValue(string $name): bool
    {
        if (array_key_exists($name, $this->values)) {
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

    /**
     * @param string $name
     *
     * @return ValueInterface|null
     */
    public function getValue(string $name): ?ValueInterface
    {
        if (array_key_exists($name, $this->values)) {
            return $this->values[$name];
        }

        throw new \InvalidArgumentException(\sprintf('Record haven\'t value %s', $name));
    }

    /**
     * @return ValueInterface[]
     */
    public function getValues(): array
    {
        return $this->values;
    }
}
