<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\ValueObject;

class ResetToken
{
    public const MAX_LENGTH = 255;

    private string $value;

    public function __construct(string $value)
    {
        if (!self::isValid($value)) {
            throw new \InvalidArgumentException('Value is not token');
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

    public function isEqual(self $action): bool
    {
        return $action->getValue() === $this->value;
    }

    public static function isValid(string $value): bool
    {
        return '' !== $value && \strlen($value) <= self::MAX_LENGTH;
    }
}
