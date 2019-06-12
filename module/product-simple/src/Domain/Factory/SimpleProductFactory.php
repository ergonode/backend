<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductSimple\Domain\Factory;

use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Entity\ProductId;
use Ergonode\Product\Domain\Factory\ProductFactoryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\ProductSimple\Domain\Entity\SimpleProduct;
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
     * @param ProductId  $id
     * @param Sku        $sku
     * @param TemplateId $templateId
     * @param array      $categories
     * @param array      $attributes
     *
     * @return AbstractProduct
     */
    public function create(ProductId $id, Sku $sku, TemplateId $templateId, array $categories = [], array $attributes = []): AbstractProduct
    {
        Assert::allIsInstanceOf($categories, CategoryCode::class);
        Assert::allIsInstanceOf($attributes, ValueInterface::class);

        return new SimpleProduct(
            $id,
            $sku,
            $templateId,
            $categories,
            $attributes
        );
    }
}
