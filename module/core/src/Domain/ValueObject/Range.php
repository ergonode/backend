<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Domain\ValueObject;

class Range
{
    private float $min;

    private float $max;

    public function __construct(float $min, float $max)
    {
        if (!self::isValid($min, $max)) {
            throw new \InvalidArgumentException('"Max" should be greater then "Min" value');
        }

        $this->min = $min;
        $this->max = $max;
    }

    public function getMin(): float
    {
        return $this->min;
    }

    public function getMax(): float
    {
        return $this->max;
    }

    public static function isValid(float $min, float $max): bool
    {
        return $min <= $max;
    }

    /**
     * @param Range $object
     */
    public function isEqual(self $object): bool
    {
        return $object->getMin() === $this->min
            && $object->getMax() === $this->max;
    }
}
