<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\SharedKernel\Domain;

abstract class AbstractCode
{
    public const MIN_LENGTH = 1;
    public const MAX_LENGTH = 128;

    private string $value;

    public function __construct(string $value)
    {
        $value = trim($value);
        if (!static::isValid($value)) {
            throw new \InvalidArgumentException(sprintf(
                '"%s" should be valid code, given value "%s"',
                static::class,
                $value
            ));
        }

        $this->value = $value;
    }

    public static function isValid(string $value): bool
    {
        $value = trim($value);

        return mb_strlen($value) <= self::MAX_LENGTH
            && mb_strlen($value) >= self::MIN_LENGTH;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function isEqual(AbstractCode $code): bool
    {
        return $code->getValue() === $this->getValue();
    }
}
