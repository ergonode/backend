<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Filter;

use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Importer\Infrastructure\Resolver\AttributeImportResolver;
use Ergonode\Product\Domain\ValueObject\Sku;

class AttributeValidationImportFilter
{
    private AttributeQueryInterface $attributeQuery;

    private AttributeImportResolver $attributeImportResolver;

    public function __construct(
        AttributeQueryInterface $attributeQuery,
        AttributeImportResolver $attributeImportResolver
    ) {
        $this->attributeQuery = $attributeQuery;
        $this->attributeImportResolver = $attributeImportResolver;
    }

    /**
     * @param TranslatableString[] $attributes
     *
     * @return TranslatableString[]
     */
    public function filter(array $attributes, string $skuValue): array
    {
        $filteredAttributes = [];
        $sku = new Sku($skuValue);
        foreach ($attributes as $codeValue => $attribute) {
            $code = new AttributeCode($codeValue);
            $attributeType = $this->attributeQuery->getAttributeTypeByCode($code);

            if (!$attributeType) {
                throw new ImportException(
                    'Attribute with {code} code does not exist',
                    ['{code}' => $code->getValue()]
                );
            }

            $filteredAttributes[$codeValue] = $this->attributeImportResolver->resolve(
                $attributeType,
                $sku,
                $attribute
            );
        }

        return $filteredAttributes;
    }
}
