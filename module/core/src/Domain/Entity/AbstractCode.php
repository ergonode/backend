<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Domain\Entity;

use JMS\Serializer\Annotation as JMS;

/**
 */
abstract class AbstractCode
{
    public const MIN_LENGTH = 1;
    public const MAX_LENGTH = 255;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        if (!self::isValid($value)) {
            throw new \InvalidArgumentException(sprintf(
                '"%s" should be valid code, given value "%s"',
                static::class,
                $value
            ));
        }

        $this->value = $value;
    }


    /**
     * @param string $value
     *
     * @return bool
     */
    public static function isValid(string $value): bool
    {
        return mb_strlen($value) <= self::MAX_LENGTH
            && mb_strlen($value) >= self::MIN_LENGTH;
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
     * @param AbstractCode $code
     *
     * @return bool
     */
    public function isEqual(AbstractCode $code): bool
    {
        return $code->getValue() === $this->getValue();
    }
}
