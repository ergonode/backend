<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Renderer;

use Ergonode\Grid\FilterInterface;

/**
 */
class FilterRenderer
{
    /**
     * @param FilterInterface $filter
     *
     * @return array
     */
    public function render(FilterInterface $filter): array
    {
        $result = [
            'type' => $filter->getType(),
            'value' => $filter->getValues(),
        ];

        return array_merge($result, $filter->render());
    }
}
