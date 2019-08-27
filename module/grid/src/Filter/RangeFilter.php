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
     * @var string
     */
    private $value;

    /**
     * @var float
     */
    private $min;

    /**
     * @var float
     */
    private $max;

    /**
     * @param float       $min
     * @param float       $max
     * @param string|null $value
     */
    public function __construct(float $min, float $max, ?string $value = null)
    {
        $this->min = $min;
        $this->max = $max;
        $this->value = $value;
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
     * @return array[]|string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param array|string $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }
}
