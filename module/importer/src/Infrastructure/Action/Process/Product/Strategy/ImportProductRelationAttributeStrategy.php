<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action\Process\Product\Strategy;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Product\Domain\Entity\Attribute\ProductRelationAttribute;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;

class ImportProductRelationAttributeStrategy implements ImportProductAttributeStrategyInterface
{
    private ProductQueryInterface $productQuery;

    public function __construct(ProductQueryInterface $productQuery)
    {
        $this->productQuery = $productQuery;
    }

    public function supported(AttributeType $type): bool
    {
        return ProductRelationAttribute::TYPE === $type->getValue();
    }

    public function build(AttributeId $id, AttributeCode $code, TranslatableString $value): ValueInterface
    {
        $result = [];
        foreach ($value->getTranslations() as $language => $valueByLanguage) {
            if (!$valueByLanguage) {
                continue;
            }
            $skuValues = explode(',', $valueByLanguage);

            if ($skuValues) {
                $productIds = [];
                foreach ($skuValues as $skuValue) {
                    $sku = new Sku($skuValue);
                    $productId = $this->productQuery->findProductIdBySku($sku);

                    if (null === $productId) {
                        throw new ImportException(
                            'Missing {sku} product for {code} relation attribute.',
                            ['{sku}' => $sku, '{code}' => $code]
                        );
                    }
                    $productIds[] = $productId->getValue();
                }
                $result[$language] = implode(',', $productIds);
            }
        }

        return new StringCollectionValue($result);
    }
}
