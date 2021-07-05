<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Validator\Strategy;

use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Product\Domain\Entity\Attribute\ProductRelationAttribute;
use Ergonode\Product\Domain\ValueObject\Sku;

class ProductRelationAttributeImportValidatorStrategy implements AttributeImportValidatorStrategyInterface
{
    public function supported(AttributeType $attributeType): bool
    {
        return $attributeType->getValue() === ProductRelationAttribute::TYPE;
    }

    public function validate(Sku $parentSku, TranslatableString $attribute): TranslatableString
    {
        foreach ($attribute->getTranslations() as $language => $valueByLanguage) {
            if (!$valueByLanguage) {
                continue;
            }
            $skuValues = explode(',', $valueByLanguage);
            $skuValuesFiltered = array_filter($skuValues, fn($e) => $e !== $parentSku->getValue());

            if ($skuValuesFiltered !== $skuValues) {
                $attribute = $attribute->change(new Language($language), implode(',', $skuValuesFiltered));
            }
        }

        return $attribute;
    }
}
