<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Domain\ValueObject;

class BatchActionType
{
    public const MAX_LENGTH = 20;

    private string $value;

    public function __construct(string $value)
    {
        $this->value = strtolower(trim($value));

        if (!self::isValid($this->value)) {
            throw new \InvalidArgumentException(\sprintf('Value "%s" is not valid batch action type', $value));
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

    public function isEqual(self $type): bool
    {
        return $type->getValue() === $this->value;
    }

    public static function isValid(string $value): bool
    {
        return '' !== $value && \strlen($value) <= self::MAX_LENGTH;
    }
}
