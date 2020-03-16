<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Factory;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Product\Domain\Entity\SimpleProduct;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

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
     * @param ProductId    $id
     * @param Sku          $sku
     * @param CategoryId[] $categories
     * @param array        $attributes
     *
     * @return AbstractProduct
     *
     * @throws \Exception
     */
    public function create(
        ProductId $id,
        Sku $sku,
        array $categories = [],
        array $attributes = []
    ): AbstractProduct {
        Assert::allIsInstanceOf($categories, CategoryId::class);
        Assert::allIsInstanceOf($attributes, ValueInterface::class);

        return new SimpleProduct(
            $id,
            $sku,
            $categories,
            $attributes
        );
    }
}
