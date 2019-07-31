<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\ValueObject;

/**
 */
class Privilege implements \JsonSerializable
{
    public const LENGTH = 128;

    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        if (!self::isValid($value)) {
            throw new \InvalidArgumentException(sprintf('%s is invalid Privilege value', $value));
        }

        $this->value = strtoupper($value);
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    public static function isValid(string $value): bool
    {
        return strlen($value) <= self::LENGTH;
    }

    /**
     * @param Privilege $value
     *
     * @return bool
     */
    public function isEqual(Privilege $value): bool
    {
        return $value->getValue() === $this->value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getSuffix(): string
    {
        return strtolower(substr(strrchr($this->value, '_'), 1));
    }

    /**
     * @return string
     */
    public function jsonSerialize(): string
    {
        return (string) $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getValue();
    }
}
