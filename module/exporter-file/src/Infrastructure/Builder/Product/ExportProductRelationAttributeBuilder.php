<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Builder\Product;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Channel\Infrastructure\Exception\ExportException;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportProductBuilderInterface;
use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportLineData;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Entity\Attribute\ProductRelationAttribute;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Infrastructure\Calculator\TranslationInheritanceCalculator;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Webmozart\Assert\Assert;

class ExportProductRelationAttributeBuilder implements ExportProductBuilderInterface
{
    public const TYPES = [
        ProductRelationAttribute::TYPE,
    ];

    private AttributeQueryInterface $attributeQuery;

    private AttributeRepositoryInterface $attributeRepository;

    private TranslationInheritanceCalculator $calculator;

    private ProductQueryInterface $productQuery;

    public function __construct(
        AttributeQueryInterface $attributeQuery,
        AttributeRepositoryInterface $attributeRepository,
        TranslationInheritanceCalculator $calculator,
        ProductQueryInterface $productQuery
    ) {
        $this->attributeQuery = $attributeQuery;
        $this->attributeRepository = $attributeRepository;
        $this->calculator = $calculator;
        $this->productQuery = $productQuery;
    }

    public function header(): array
    {
        return $this->attributeQuery->getAttributeCodes(self::TYPES, false);
    }

    public function build(AbstractProduct $product, ExportLineData $result, Language $language): void
    {
        foreach ($this->attributeQuery->getAttributeCodes(self::TYPES, false) as $attributeCode) {
            $result->set($attributeCode);
            $code = new AttributeCode($attributeCode);
            if ($product->hasAttribute($code)) {
                $value = $product->getAttribute($code);
                $attribute = $this->getAttribute($code);
                $calculatedValue = $this->calculator->calculate($attribute->getScope(), $value, $language);
                if (null !== $calculatedValue) {
                    if (!is_array($calculatedValue)) {
                        throw new ExportException(
                            sprintf(
                                'Can\'t calculate value for attribute "%s" in product "%s"',
                                $attributeCode,
                                $product->getSku()->getValue()
                            )
                        );
                    }
                    $result->set($code->getValue(), $this->findSku($calculatedValue));
                }
            }
        }
    }

    private function getAttribute(AttributeCode $code): AbstractAttribute
    {
        $attributeId = $this->attributeQuery->findAttributeIdByCode($code);
        Assert::notNull($attributeId);
        $attribute = $this->attributeRepository->load($attributeId);
        Assert::isInstanceOf($attribute, AbstractAttribute::class);

        return $attribute;
    }

    private function findSku(array $value): string
    {
        $result = [];
        foreach ($value as $productId) {
            $sku = $this->productQuery->findSkuByProductId(new ProductId($productId));
            if (!$sku) {
                throw new \RuntimeException("There's no product with [$productId] id.");
            }
            $result[] = $sku->getValue();
        }

        return implode(',', $result);
    }
}
