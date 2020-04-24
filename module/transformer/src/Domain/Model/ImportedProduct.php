<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Domain\Model;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

/**
 */
class ImportedProduct
{
    /**
     * @var string
     */
    public string $sku;

    /**
     * @var array
     */
    public array $attributes;

    /**
     * @var CategoryId[]
     */
    public array $categories;

    /**
     * @param string $sku
     */
    public function __construct(string $sku)
    {
        $this->sku = $sku;
        $this->attributes = [];
        $this->categories = [];
    }
}
