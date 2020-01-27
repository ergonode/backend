<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Domain\ValueObject;

/**
 */
class ProductCollectionCode
{
    private const PATTERN = '/[a-zA-Z0-9-_]+\b/i';

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
            throw new \InvalidArgumentException('Invalid product collection code value');
        }

        $this->value = $value;
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
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    public static function isValid(string $value): bool
    {
        if (preg_match(self::PATTERN, $value, $matches) === 0) {
            return false;
        }

        if (trim($value) !== $value) {
            return false;
        }

        return strlen($value) < 256;
    }
}
