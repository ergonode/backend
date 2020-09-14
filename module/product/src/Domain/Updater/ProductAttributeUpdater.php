<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Updater;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Webmozart\Assert\Assert;
use Ergonode\Value\Domain\ValueObject\ValueInterface;

/**
 */
class ProductAttributeUpdater
{
    /**
     * @param AbstractProduct $product
     * @param array           $attributes
     *
     * @throws \Exception
     */
    public function update(AbstractProduct $product, array $attributes): void
    {
        Assert::allIsInstanceOf($attributes, ValueInterface::class);

        foreach ($attributes as $code => $attribute) {
            $attributeCode = new AttributeCode($code);
            if ($product->hasAttribute($attributeCode)) {
                $product->changeAttribute($attributeCode, $attribute);
            } else {
                $product->addAttribute($attributeCode, $attribute);
            }
        }

        foreach ($product->getAttributes() as $code => $value) {
            $attributeCode = new AttributeCode($code);
            if (!array_key_exists($code, $attributes)) {
                $product->removeAttribute($attributeCode);
            }
        }
    }
}
