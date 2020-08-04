<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Product;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractDateAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractNumericAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractTextAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractUnitAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Calculator\AttributeTranslationInheritanceCalculator;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6PropertyGroupOptionClient;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Shopware6ProductMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Webmozart\Assert\Assert;

/**
 */
class Shopware6ProductPropertyGroupMapper implements Shopware6ProductMapperInterface
{
    private const SUPPORTED_TYPE = [
        AbstractTextAttribute::TYPE,
        AbstractDateAttribute::TYPE,
        AbstractNumericAttribute::TYPE,
        AbstractUnitAttribute::TYPE,
    ];

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
     * @param Shopware6Product $shopware6Product
     * @param AbstractProduct  $product
     * @param Shopware6Channel $channel
     *
     * @return Shopware6Product
     */
    public function map(
        Shopware6Product $shopware6Product,
        AbstractProduct $product,
        Shopware6Channel $channel
    ): Shopware6Product {

        foreach ($channel->getPropertyGroup() as $attributeId) {
            $this->attributeMap($shopware6Product, $attributeId, $product, $channel);
        }

        return $shopware6Product;
    }

    /**
     * @param Shopware6Product $shopware6Product
     * @param AttributeId      $attributeId
     * @param AbstractProduct  $product
     * @param Shopware6Channel $channel
     *
     * @return Shopware6Product
     */
    private function attributeMap(
        Shopware6Product $shopware6Product,
        AttributeId $attributeId,
        AbstractProduct $product,
        Shopware6Channel $channel
    ): Shopware6Product {
        $attribute = $this->repository->load($attributeId);
        Assert::notNull($attribute);
        if (in_array($attribute->getType(), self::SUPPORTED_TYPE, true)) {
            if (false === $product->hasAttribute($attribute->getCode())) {
                return $shopware6Product;
            }

            $value = $product->getAttribute($attribute->getCode());
            $calculateValue = $this->calculator->calculate($attribute, $value, $channel->getDefaultLanguage());
            if ($calculateValue) {
                $shopware6Product->addProperty($this->loadPropertyOptionId($channel, $attribute, $calculateValue));
            }
        }

        return $shopware6Product;
    }

    /**
     * @param Shopware6Channel  $channel
     * @param AbstractAttribute $attribute
     * @param                   $value
     *
     * @return string
     */
    private function loadPropertyOptionId(
        Shopware6Channel $channel,
        AbstractAttribute $attribute,
        $value
    ): string {
        $propertyGroupOption = $this->propertyGroupOptionClient->findByNameOrCreate(
            $channel,
            $attribute->getId(),
            $value
        );

        return $propertyGroupOption->getId();
    }
}
