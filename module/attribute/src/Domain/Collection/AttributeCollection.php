<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Collection;

use Ergonode\Value\Domain\ValueObject\ValueInterface;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

/**
 */
class AttributeCollection implements \Countable, \IteratorAggregate, \ArrayAccess
{
    /**
     * @var ValueInterface[]
     *
     * @JMS\Type(array<string, Ergonode\Value\Domain\ValueObject\ValueInterface>)
     */
    private array $collection;

    /**
     * @param $collection
     */
    public function __construct($collection)
    {
        Assert::allIsInstanceOf($collection, ValueInterface::class);
        Assert::allString(array_keys($collection));

        $this->collection = $collection;
    }

    /**
     * @return \Countable
     */
    public function getIterator(): \Countable
    {
        return new \ArrayIterator($this->collection);
    }

    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->collection);
    }

    /**
     * @param mixed $offset
     *
     * @return ValueInterface
     */
    public function offsetGet($offset): ValueInterface
    {
        return $this->collection[$offset];
    }

    /**
     * @param string         $offset
     * @param ValueInterface $value
     */
    public function offsetSet($offset, $value): void
    {
        Assert::isInstanceOf($value, ValueInterface::class);

        $this->collection[$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset): void
    {
        unset($this->collection[$offset]);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->collection);
    }
}
