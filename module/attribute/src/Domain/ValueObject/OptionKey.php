<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\ValueObject;

class OptionKey
{
    public const MAX_LENGTH = 255;

    private string $value;

    public function __construct(string $value)
    {
        $this->value = strtolower(trim($value));

        if (!self::isValid($this->value)) {
            throw new \InvalidArgumentException(\sprintf('Value "%s" is not valid option key', $value));
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function isEqual(OptionKey $code): bool
    {
        return $code->value === $this->value;
    }

    public static function isValid(string $value): bool
    {
        return '' !== $value && \strlen($value) <= self::MAX_LENGTH;
    }
}
