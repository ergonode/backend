<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Product;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\AbstractOption;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\Repository\OptionRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Calculator\AttributeTranslationInheritanceCalculator;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6PropertyGroupOptionClient;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroupOption;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\AggregateId;

/**
 */
abstract class AbstractShopware6ProductPropertyGroupOptionMapper extends AbstractShopware6ProductPropertyGroupMapper
{
    /**
     * @var OptionRepositoryInterface
     */
    private OptionRepositoryInterface $optionRepository;

    /**
     * @param AttributeRepositoryInterface              $repository
     * @param Shopware6PropertyGroupOptionClient        $propertyGroupOptionClient
     * @param AttributeTranslationInheritanceCalculator $calculator
     * @param OptionRepositoryInterface                 $optionRepository
     */
    public function __construct(
        AttributeRepositoryInterface $repository,
        Shopware6PropertyGroupOptionClient $propertyGroupOptionClient,
        AttributeTranslationInheritanceCalculator $calculator,
        OptionRepositoryInterface $optionRepository
    ) {
        parent::__construct($repository, $propertyGroupOptionClient, $calculator);
        $this->optionRepository = $optionRepository;
    }

    /**
     * {@inheritDoc}
     */
    protected function addProperty(
        Shopware6Product $shopware6Product,
        AbstractAttribute $attribute,
        AbstractProduct $product,
        Shopware6Channel $channel,
        ?Language $language = null
    ): Shopware6Product {

        $value = $product->getAttribute($attribute->getCode());
        $calculateValue = $this->calculator->calculate($attribute, $value, $channel->getDefaultLanguage());
        if ($calculateValue) {
            $options = explode(',', $calculateValue);
            foreach ($options as $optionValue) {
                $optionId = new AggregateId($optionValue);
                $option = $this->optionRepository->load($optionId);
                if ($option) {
                    $propertyGroupOptions = $this->createPropertyGroupOptionsFromOptions($channel, $option);
                    $propertyId = $this->propertyGroupOptionClient->findByNameOrCreate(
                        $channel,
                        $attribute->getId(),
                        $propertyGroupOptions
                    );
                    if ($propertyId) {
                        $shopware6Product->addProperty($propertyId);
                    }
                }
            }
        }

        return $shopware6Product;
    }

    /**
     * @param Shopware6Channel $channel
     * @param AbstractOption   $option
     *
     * @return Shopware6PropertyGroupOption
     */
    private function createPropertyGroupOptionsFromOptions(
        Shopware6Channel $channel,
        AbstractOption $option
    ): Shopware6PropertyGroupOption {
        $name = $name = $option->getLabel()->get($channel->getDefaultLanguage());

        $propertyGroupOption = new Shopware6PropertyGroupOption(null, $name ?: $option->getCode()->getValue());

        foreach ($channel->getLanguages() as $language) {
            $calculateValue = $option->getLabel()->get($language);
            if ($calculateValue) {
                $propertyGroupOption->addTranslations($language, 'name', $calculateValue);
            }
        }

        return $propertyGroupOption;
    }
}
