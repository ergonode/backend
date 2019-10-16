<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Filter;

use Ergonode\Grid\FilterInterface;

/**
 */
class RangeFilter implements FilterInterface
{
    public const TYPE = 'RANGE';

    /**
     * @var array
     */
    private $values;

    /**
     * @var float
     */
    private $min;

    /**
     * @var float
     */
    private $max;

    /**
     * @param float $min
     * @param float $max
     * @param array $values
     */
    public function __construct(float $min, float $max, array $values = [])
    {
        $this->min = $min;
        $this->max = $max;
        $this->values = $values;
    }

    /**
     * @return array
     */
    public function render(): array
    {
        return [
            'min' => $this->min,
            'max' => $this->max,
        ];
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return bool
     */
    public function isEqual(): bool
    {
        return false;
    }

    /**
     * @return array[]
     */
    public function getValues(): array
    {
        return $this->values;
    }
}
