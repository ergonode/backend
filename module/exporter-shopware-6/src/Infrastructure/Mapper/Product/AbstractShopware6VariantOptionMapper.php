<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Product;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\Repository\OptionRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6PropertyGroupOptionsRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Calculator\AttributeTranslationInheritanceCalculator;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Shopware6ProductMapperInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Webmozart\Assert\Assert;

/**
 */
abstract class AbstractShopware6VariantOptionMapper implements Shopware6ProductMapperInterface
{
    /**
     * @var AttributeRepositoryInterface
     */
    protected AttributeRepositoryInterface $attributeRepository;

    /**
     * @var OptionRepositoryInterface
     */
    protected OptionRepositoryInterface $optionRepository;

    /**
     * @var AttributeTranslationInheritanceCalculator
     */
    protected AttributeTranslationInheritanceCalculator $calculator;

    /**
     * @var Shopware6PropertyGroupOptionsRepositoryInterface
     */
    protected Shopware6PropertyGroupOptionsRepositoryInterface $propertyGroupOptionsRepository;

    /**
     * @param AttributeRepositoryInterface                     $attributeRepository
     * @param OptionRepositoryInterface                        $optionRepository
     * @param AttributeTranslationInheritanceCalculator        $calculator
     * @param Shopware6PropertyGroupOptionsRepositoryInterface $propertyGroupOptionsRepository
     */
    public function __construct(
        AttributeRepositoryInterface $attributeRepository,
        OptionRepositoryInterface $optionRepository,
        AttributeTranslationInheritanceCalculator $calculator,
        Shopware6PropertyGroupOptionsRepositoryInterface $propertyGroupOptionsRepository
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->optionRepository = $optionRepository;
        $this->calculator = $calculator;
        $this->propertyGroupOptionsRepository = $propertyGroupOptionsRepository;
    }

    /**
     * @param AttributeId      $bindingId
     * @param AbstractProduct  $product
     * @param Shopware6Channel $channel
     *
     * @return string|null
     */
    protected function optionMapper(
        AttributeId $bindingId,
        AbstractProduct $product,
        Shopware6Channel $channel
    ): ?string {
        $binding = $this->attributeRepository->load($bindingId);
        Assert::notNull($binding);
        if (false === $product->hasAttribute($binding->getCode())) {
            return null;
        }
        $value = $product->getAttribute($binding->getCode());
        $optionValue = $this->calculator->calculate($binding, $value, $channel->getDefaultLanguage());
        $optionId = new AggregateId($optionValue);

        return $this->optionMap($channel, $bindingId, $optionId);
    }

    /**
     * @param Shopware6Channel $channel
     * @param AttributeId      $bindingId
     * @param AggregateId      $optionId
     *
     * @return string|null
     */
    protected function optionMap(Shopware6Channel $channel, AttributeId $bindingId, AggregateId $optionId): ?string
    {
        return $this->propertyGroupOptionsRepository->load(
            $channel->getId(),
            $bindingId,
            $optionId
        );
    }
}
