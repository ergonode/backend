<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\ValueObject;

class PrivilegeEndPoint
{
    public const LENGTH = 128;

    private string $value;

    public function __construct(string $value)
    {
        $value = mb_strtoupper($value);
        if (!self::isValid($value)) {
            throw new \InvalidArgumentException(sprintf('%s is invalid Privilege EndPoint value', $value));
        }

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public static function isValid(string $value): bool
    {
        $value = mb_strtoupper($value);

        return mb_strlen($value) <= self::LENGTH;
    }

    public function isEqual(PrivilegeEndPoint $value): bool
    {
        return $value->getValue() === $this->value;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}
