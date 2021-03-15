<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\ValueObject;

class AttributeScope
{
    public const LOCAL = 'local';
    public const GLOBAL = 'global';

    public const AVAILABLE = [
        self::LOCAL,
        self::GLOBAL,
    ];

    private string $value;

    public function __construct(string $value)
    {
        $this->value = trim($value);

        if (!self::isValid($value)) {
            throw new \InvalidArgumentException(\sprintf('Unsupported "%s" value range', $value));
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public static function isValid(string $value): bool
    {
        return \in_array($value, self::AVAILABLE, true);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function isLocal(): bool
    {
        return self::LOCAL === $this->value;
    }

    public function isGlobal(): bool
    {
        return self::GLOBAL === $this->value;
    }

    public function isEqual(AttributeScope $value): bool
    {
        return $value->value === $this->value;
    }
}
