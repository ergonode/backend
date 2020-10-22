<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 *  See LICENSE.txt for license details.
 *
 */

namespace Ergonode\Grid\Filter;

use Ergonode\Grid\FilterInterface;

class DateFilter implements FilterInterface
{
    public const TYPE = 'DATE';

    /**
     * @return array
     */
    public function render(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}
