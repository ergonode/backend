<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Factory;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\ValueObject\ProductType;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

/**
 */
interface ProductFactoryInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function isSupportedBy(string $type): bool;

    /**
     * @param ProductId   $id
     * @param Sku         $sku
     * @param ProductType $type
     * @param array       $categories
     * @param array       $attributes
     *
     * @return AbstractProduct
     */
    public function create(
        ProductId $id,
        Sku $sku,
        ProductType $type,
        array $categories = [],
        array $attributes = []
    ): AbstractProduct;
}
