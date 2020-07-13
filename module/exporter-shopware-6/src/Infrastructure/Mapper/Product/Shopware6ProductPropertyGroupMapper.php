<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Product;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\ExporterShopware6\Infrastructure\Calculator\AttributeTranslationInheritanceCalculator;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6PropertyGroupOptionClient;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Shopware6ProductMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

/**
 */
class Shopware6ProductPropertyGroupMapper implements Shopware6ProductMapperInterface
{
    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $repository;

    /**
     * @var AttributeTranslationInheritanceCalculator
     */
    private AttributeTranslationInheritanceCalculator $calculator;

    /**
     * @var Shopware6PropertyGroupOptionClient
     */
    private Shopware6PropertyGroupOptionClient $propertyGroupOptionClient;

    /**
     * @param AttributeRepositoryInterface              $repository
     * @param AttributeTranslationInheritanceCalculator $calculator
     * @param Shopware6PropertyGroupOptionClient        $propertyGroupOptionClient
     */
    public function __construct(
        AttributeRepositoryInterface $repository,
        AttributeTranslationInheritanceCalculator $calculator,
        Shopware6PropertyGroupOptionClient $propertyGroupOptionClient
    ) {
        $this->repository = $repository;
        $this->calculator = $calculator;
        $this->propertyGroupOptionClient = $propertyGroupOptionClient;
    }

    /**
     * @param Shopware6Product          $shopware6Product
     * @param AbstractProduct           $product
     * @param Shopware6ExportApiProfile $profile
     *
     * @return Shopware6Product
     */
    public function map(
        Shopware6Product $shopware6Product,
        AbstractProduct $product,
        Shopware6ExportApiProfile $profile
    ): Shopware6Product {

        foreach ($profile->getPropertyGroup() as $attributeId) {
            $this->attributeMap($shopware6Product, $attributeId, $product, $profile);
        }

        return $shopware6Product;
    }

    /**
     * @param Shopware6Product          $shopware6Product
     * @param AttributeId               $attributeId
     * @param AbstractProduct           $product
     * @param Shopware6ExportApiProfile $profile
     *
     * @return Shopware6Product
     */
    private function attributeMap(
        Shopware6Product $shopware6Product,
        AttributeId $attributeId,
        AbstractProduct $product,
        Shopware6ExportApiProfile $profile
    ): Shopware6Product {
        $attribute = $this->repository->load($attributeId);
        if (false === $product->hasAttribute($attribute->getCode())) {
            return $shopware6Product;
        }

        $value = $product->getAttribute($attribute->getCode());
        $calculateValue = $this->calculator->calculate($attribute, $value, $profile->getDefaultLanguage());
        if ($calculateValue) {
            $shopware6Product->addProperty($this->loadPropertyOptionId($profile, $attribute, $calculateValue));
        }

        return $shopware6Product;
    }

    /**
     * @param Shopware6ExportApiProfile $profile
     * @param AbstractAttribute         $attribute
     * @param                           $value
     *
     * @return string
     */
    private function loadPropertyOptionId(
        Shopware6ExportApiProfile $profile,
        AbstractAttribute $attribute,
        $value
    ): string {
        $propertyGroupOption = $this->propertyGroupOptionClient->findByNameOrCreate(
            $profile,
            $attribute->getId(),
            $value
        );

        return $propertyGroupOption->getId();
    }
}
