<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Product;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Calculator\AttributeTranslationInheritanceCalculator;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6PropertyGroupOptionClient;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Shopware6ProductMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroupOption;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Webmozart\Assert\Assert;

/**
 */
abstract class AbstractShopware6ProductPropertyGroupMapper implements Shopware6ProductMapperInterface
{
    /**
     * @var AttributeRepositoryInterface
     */
    protected AttributeRepositoryInterface $repository;

    /**
     * @var Shopware6PropertyGroupOptionClient
     */
    protected Shopware6PropertyGroupOptionClient $propertyGroupOptionClient;

    /**
     * @var AttributeTranslationInheritanceCalculator
     */
    protected AttributeTranslationInheritanceCalculator $calculator;

    /**
     * @param AttributeRepositoryInterface              $repository
     * @param Shopware6PropertyGroupOptionClient        $propertyGroupOptionClient
     * @param AttributeTranslationInheritanceCalculator $calculator
     */
    public function __construct(
        AttributeRepositoryInterface $repository,
        Shopware6PropertyGroupOptionClient $propertyGroupOptionClient,
        AttributeTranslationInheritanceCalculator $calculator
    ) {
        $this->repository = $repository;
        $this->propertyGroupOptionClient = $propertyGroupOptionClient;
        $this->calculator = $calculator;
    }

    /**
     * {@inheritDoc}
     */
    public function map(
        Shopware6Product $shopware6Product,
        AbstractProduct $product,
        Shopware6Channel $channel,
        ?Language $language = null
    ): Shopware6Product {
        foreach ($channel->getPropertyGroup() as $attributeId) {
            $this->attributeMap($shopware6Product, $attributeId, $product, $channel, $language);
        }

        return $shopware6Product;
    }

    /**
     * @return string
     */
    abstract public function getType(): string;

    /**
     * @param string $type
     *
     * @return bool
     */
    protected function isSupported(string $type): bool
    {
        return $this->getType() === $type;
    }

    /**
     * @param Shopware6Channel  $channel
     * @param AbstractAttribute $attribute
     * @param ValueInterface    $value
     *
     * @return Shopware6PropertyGroupOption
     */
    protected function createPropertyGroupOptions(
        Shopware6Channel $channel,
        AbstractAttribute $attribute,
        ValueInterface $value
    ): Shopware6PropertyGroupOption {
        $name = $this->calculator->calculate($attribute, $value, $channel->getDefaultLanguage());

        $propertyGroupOption = new Shopware6PropertyGroupOption(null, $name);

        foreach ($channel->getLanguages() as $language) {
            $calculateValue = $this->calculator->calculate($attribute, $value, $language);
            if ($calculateValue) {
                $propertyGroupOption->addTranslations($language, 'name', $calculateValue);
            }
        }

        return $propertyGroupOption;
    }

    /**
     * @param Shopware6Product  $shopware6Product
     * @param AbstractAttribute $attribute
     * @param AbstractProduct   $product
     * @param Shopware6Channel  $channel
     * @param Language|null     $language
     *
     * @return Shopware6Product
     */
    abstract protected function addProperty(
        Shopware6Product $shopware6Product,
        AbstractAttribute $attribute,
        AbstractProduct $product,
        Shopware6Channel $channel,
        ?Language $language = null
    ): Shopware6Product;

    /**
     * @param Shopware6Product $shopware6Product
     * @param AttributeId      $attributeId
     * @param AbstractProduct  $product
     * @param Shopware6Channel $channel
     * @param Language|null    $language
     *
     * @return Shopware6Product
     */
    private function attributeMap(
        Shopware6Product $shopware6Product,
        AttributeId $attributeId,
        AbstractProduct $product,
        Shopware6Channel $channel,
        ?Language $language = null
    ): Shopware6Product {
        $attribute = $this->repository->load($attributeId);
        Assert::notNull($attribute);

        if (false === $product->hasAttribute($attribute->getCode())) {
            return $shopware6Product;
        }

        if ($this->isSupported($attribute->getType())) {
            $this->addProperty($shopware6Product, $attribute, $product, $channel, $language);
        }

        return $shopware6Product;
    }
}
