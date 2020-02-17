<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
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
    private string $value;

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
        return $this->getValue();
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    public static function isValid(string $value): bool
    {
        $length = mb_strlen($value);

        return self::MIN_LENGTH <= $length && self::MAX_LENGTH >= $length;
    }
}
