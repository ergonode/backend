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
     * @var Range
     */
    private Range $range;

    /**
     * @param Range $range
     */
    public function __construct(Range $range)
    {
        $this->range = $range;
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
}
