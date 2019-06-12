<?php

/**
 * Copyright © Ergonaut Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\ValueObject;

/**
 */
class Password
{
    public const MIN_LENGTH = 6;
    public const MAX_LENGTH = 32;

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
            throw new \InvalidArgumentException('Value is not correct password');
        }
        $this->value = $value;
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
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    public static function isValid(string $value): bool
    {
        if (strlen($value) < self::MIN_LENGTH) {
            return false;
        }

        if (strlen($value) > self::MAX_LENGTH) {
            return false;
        }

        return true;
    }
}
