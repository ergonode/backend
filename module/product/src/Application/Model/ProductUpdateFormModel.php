<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Model;

/**
 */
class ProductUpdateFormModel
{
    /**
     * @var array
     */
    public array $categories;

    /**
     */
    public function __construct()
    {
        $this->categories = [];
    }
}
