<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Validator\Strategy;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Product\Domain\Entity\Attribute\ProductRelationAttribute;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;

class ProductRelationAttributeImportValidator implements AttributeImportValidatorInterface
{
    private ProductQueryInterface $productQuery;

    public function __construct(ProductQueryInterface $productQuery)
    {
        $this->productQuery = $productQuery;
    }


    public function supported(AttributeType $attributeType): bool
    {
        return $attributeType->getValue() === ProductRelationAttribute::TYPE;
    }

    public function validate(AttributeCode $code, TranslatableString $attribute): bool
    {
        foreach ($attribute->getTranslations() as $valueByLanguage) {
            if (!$valueByLanguage) {
                continue;
            }
            $skuValues = explode(',', $valueByLanguage);

            if ($skuValues) {
                foreach ($skuValues as $skuValue) {
                    $sku = new Sku($skuValue);
                    $productId = $this->productQuery->findProductIdBySku($sku);

                    if (null === $productId) {
                        return false;
                    }
                }
            }
        }

        return true;
    }
}
