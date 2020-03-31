<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Factory;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Entity\SimpleProduct;
use Ergonode\Product\Domain\ValueObject\ProductType;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Webmozart\Assert\Assert;

/**
 */
class SimpleProductFactory implements ProductFactoryInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function isSupportedBy(string $type): bool
    {
        return $type === SimpleProduct::TYPE;
    }

    /**
     * @param ProductId   $id
     * @param Sku         $sku
     * @param ProductType $type
     * @param array       $categories
     * @param array       $attributes
     *
     * @return AbstractProduct
     * @throws \Exception
     */
    public function create(
        ProductId $id,
        Sku $sku,
        ProductType $type,
        array $categories = [],
        array $attributes = []
    ): AbstractProduct {
        Assert::allIsInstanceOf($categories, CategoryId::class);
        Assert::allIsInstanceOf($attributes, ValueInterface::class);

        return new SimpleProduct(
            $id,
            $sku,
            $type,
            $categories,
            $attributes
        );
    }
}
