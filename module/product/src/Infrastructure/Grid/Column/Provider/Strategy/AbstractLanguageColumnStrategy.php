<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Grid\Request\FilterCollection;

/**
 */
abstract class AbstractLanguageColumnStrategy implements AttributeColumnStrategyInterface
{
    /**
     * @param string           $columnKey
     * @param string           $languageCode
     * @param FilterCollection $filter
     *
     * @return string
     */
    protected function getFilterKey(string $columnKey, string $languageCode, FilterCollection $filter): string
    {
        $filterKey = $columnKey.':'.$languageCode;
        if (!$filter->has($filterKey)) {
            $filterKey = $columnKey;
        }

        return $filterKey;
    }
}
