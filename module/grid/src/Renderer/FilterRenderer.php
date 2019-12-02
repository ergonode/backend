<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Renderer;

use Ergonode\Grid\FilterInterface;
use Ergonode\Grid\Request\FilterCollection;

/**
 */
class FilterRenderer
{
    /**
     * @param string           $key
     * @param FilterInterface  $filter
     *
     * @param FilterCollection $filters
     *
     * @return array
     */
    public function render(string $key, FilterInterface $filter, FilterCollection $filters): array
    {
        $result = [
            'type' => $filter->getType(),
            'value' => $filters->has($key) ? $filter->getValues() : null,
        ];

        return array_merge($result, $filter->render());
    }
}
