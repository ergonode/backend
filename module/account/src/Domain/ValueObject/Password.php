<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\ValueObject;

class Password
{
    public const MIN_LENGTH = 6;
    public const MAX_LENGTH = 62;

    private string $value;

    public function __construct(string $value)
    {
        if (!self::isValid($value)) {
            throw new \InvalidArgumentException('Value is not correct password');
        }

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }

    public static function isValid(string $value): bool
    {
        $length = mb_strlen($value);

        return self::MIN_LENGTH <= $length && self::MAX_LENGTH >= $length;
    }
}
