<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Product;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\Repository\OptionRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Calculator\AttributeTranslationInheritanceCalculator;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6PropertyGroupOptionClient;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Shopware6ProductMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Webmozart\Assert\Assert;

/**
 */
class Shopware6ProductPropertyGroupSelectMapper implements Shopware6ProductMapperInterface
{
    private const SUPPORTED_TYPE = [
        SelectAttribute::TYPE,
        MultiSelectAttribute::TYPE,
    ];

    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $repository;

    /**
     * @var OptionRepositoryInterface
     */
    private OptionRepositoryInterface $optionRepository;

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
     * @param OptionRepositoryInterface                 $optionRepository
     * @param AttributeTranslationInheritanceCalculator $calculator
     * @param Shopware6PropertyGroupOptionClient        $propertyGroupOptionClient
     */
    public function __construct(
        AttributeRepositoryInterface $repository,
        OptionRepositoryInterface $optionRepository,
        AttributeTranslationInheritanceCalculator $calculator,
        Shopware6PropertyGroupOptionClient $propertyGroupOptionClient
    ) {
        $this->repository = $repository;
        $this->optionRepository = $optionRepository;
        $this->calculator = $calculator;
        $this->propertyGroupOptionClient = $propertyGroupOptionClient;
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
                $options = explode(',', $calculateValue);
                foreach ($options as $optionValue) {
                    $optionId = new AggregateId($optionValue);
                    $option = $this->optionRepository->load($optionId);
                    if ($option) {
                        $name = $option->getLabel()->get($channel->getDefaultLanguage());
                        $name = $name ?: $option->getCode()->getValue();
                        $shopware6Product->addProperty($this->loadPropertyOptionId($channel, $attribute, $name));
                    }
                }
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

        return 'xxxx';
//        return $this->propertyGroupOptionClient->findByNameOrCreate(
//            $channel,
//            $attribute->getId(),
//            $value
//        );

//        return $propertyGroupOption->getId();
    }
}
