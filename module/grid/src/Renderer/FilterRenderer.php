<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Renderer;

use Ergonode\Grid\FilterInterface;
use Ergonode\Grid\Request\FilterValueCollection;

/**
 */
class FilterRenderer
{
    /**
     * @param string                $key
     * @param FilterInterface       $filter
     *
     * @param FilterValueCollection $filters
     *
     * @return array
     */
    public function render(string $key, FilterInterface $filter, FilterValueCollection $filters): array
    {
        $result = [
            'type' => $filter->getType(),
        ];

        return array_merge($result, $filter->render());
    }
}
