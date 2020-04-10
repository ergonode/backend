<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\ValueObject;

/**
 */
class LanguagePrivilege
{
    public const LENGTH = 128;

    /**
     * @var string
     */
    private string $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        if (!self::isValid($value)) {
            throw new \InvalidArgumentException(sprintf('%s is invalid Language Privilege value', $value));
        }

        $this->value = mb_strtoupper($value);
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    public static function isValid(string $value): bool
    {
        return mb_strlen($value) <= self::LENGTH;
    }

    /**
     * @param LanguagePrivilege $value
     *
     * @return bool
     */
    public function isEqual(LanguagePrivilege $value): bool
    {
        return $value->getValue() === $this->value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getValue();
    }
}
