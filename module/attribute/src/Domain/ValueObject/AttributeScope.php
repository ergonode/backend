<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\ValueObject;

/**
 */
class AttributeScope
{
    public const LOCAL = 'local';
    public const GLOBAL = 'global';

    public const AVAILABLE = [
        self::LOCAL,
        self::GLOBAL,
    ];

    /**
     * @var string
     */
    private string $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = trim($value);

        if (!self::isValid($value)) {
            throw new \InvalidArgumentException(\sprintf('Unsupported "%s" value range', $value));
        }
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    public static function isValid(string $value): bool
    {
        return \in_array($value, self::AVAILABLE, true);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * @param AttributeScope $value
     *
     * @return bool
     */
    public function isEqual(AttributeScope $value): bool
    {
        return $value->value === $this->value;
    }
}
