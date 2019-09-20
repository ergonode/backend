<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\ValueObject;

/**
 */
class AttributeValueType
{
    public const ALPHA = 'ALPHA';
    public const ALPHA_NUMBER = 'ALPHA_NUMBER';
    public const NUMBER = 'NUMBER';
    public const DIGIT = 'DIGIT';

    public const AVAILABLE = [
        self::ALPHA,
        self::ALPHA_NUMBER,
        self::NUMBER,
        self::DIGIT,
    ];

    /**
     * @var string;
     */
    private $value;

    /**
     * AttributeValueType constructor.
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->value = strtoupper(trim($value));
        if (!\in_array($this->value, self::AVAILABLE, true)) {
            throw new \InvalidArgumentException(
                \sprintf(
                    'Required value must be one of "%s"',
                    implode(', ', self::AVAILABLE)
                )
            );
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
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
