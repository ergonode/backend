<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Domain\ValueObject;

/**
 * Value object representing color in a hexadecimal notation
 */
class Color
{
    private const PATTERN  = '/#([A-Fa-f0-9]{3}){1,2}\b/i';

    private string $value;

    public function __construct(string $value)
    {
        if (!$this::isValid($value)) {
            throw new \InvalidArgumentException(\sprintf('Value "%s" is not valid hexadecimal color value', $value));
        }

        $this->value = strtoupper($value);
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
        return preg_match(self::PATTERN, $value, $matches) > 0;
    }

    public function isEqual(Color $color): bool
    {
        return $color->getValue() === $this->value;
    }
}
