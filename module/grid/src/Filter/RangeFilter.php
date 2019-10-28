<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Filter;

use Ergonode\Core\Domain\ValueObject\Range;
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
     * @var Range
     */
    private $range;

    /**
     * @param Range $range
     * @param array $values
     */
    public function __construct(Range $range, array $values = [])
    {
        $this->range = $range;
        $this->values = $values;
    }

    /**
     * @return array
     */
    public function render(): array
    {
        return [
            'min' => $this->range->getMin(),
            'max' => $this->range->getMax(),
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
