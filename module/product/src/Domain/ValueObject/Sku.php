<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\ValueObject;

class Sku
{
    private const MIN_LENGTH = 1;
    private const MAX_LENGTH = 255;

    private string $value;

    public function __construct(string $value)
    {
        $value = trim($value);

        if (!self::isValid($value)) {
            throw new \InvalidArgumentException(\sprintf('Sku "%s" is incorrect', $value));
        }

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public static function isValid(string $value): bool
    {
        return
            mb_strlen(trim($value)) >= self::MIN_LENGTH
            && mb_strlen(trim($value)) <= self::MAX_LENGTH;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
