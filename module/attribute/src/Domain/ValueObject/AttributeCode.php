<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\ValueObject;

class AttributeCode
{
    private const PATTERN = '/^([a-zA-Z0-9_]+)$/';
    private const LENGTH = 128;

    private string $value;

    public function __construct(string $value)
    {
        $this->value = strtolower(trim($value));

        if (!self::isValid($this->value)) {
            throw new \InvalidArgumentException(\sprintf('Value "%s" is not valid attribute code', $value));
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

    public static function isValid(string $value): bool
    {
        return preg_match(self::PATTERN, $value)
            && strlen($value) <= self::LENGTH;
    }
}
