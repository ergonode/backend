<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Product;

use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\Repository\OptionRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6PropertyGroupOptionsRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6PropertyGroupRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Calculator\AttributeTranslationInheritanceCalculator;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\ExporterShopware6\Infrastructure\Model\Product\Shopware6ProductConfiguratorSettings;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Entity\VariableProduct;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\AggregateId;

class Shopware6ProductVariantMapper extends AbstractShopware6VariantOptionMapper
{
    private Shopware6PropertyGroupRepositoryInterface $propertyGroupRepository;
    private OptionQueryInterface  $optionQuery;

    public function __construct(
        Shopware6PropertyGroupRepositoryInterface $propertyGroupRepository,
        OptionQueryInterface $optionQuery,
        AttributeRepositoryInterface $attributeRepository,
        OptionRepositoryInterface $optionRepository,
        AttributeTranslationInheritanceCalculator $calculator,
        Shopware6PropertyGroupOptionsRepositoryInterface $propertyGroupOptionsRepository
    ) {
        parent::__construct($attributeRepository, $optionRepository, $calculator, $propertyGroupOptionsRepository);

        $this->propertyGroupRepository = $propertyGroupRepository;
        $this->optionQuery = $optionQuery;
    }

    public function map(
        Shopware6Channel $channel,
        Export $export,
        Shopware6Product $shopware6Product,
        AbstractProduct $product,
        ?Language $language = null
    ): Shopware6Product {
        if ($product instanceof VariableProduct) {
            $this->variantMapper($channel, $shopware6Product, $product);
        }

        return $shopware6Product;
    }

    private function variantMapper(
        Shopware6Channel $channel,
        Shopware6Product $shopware6Product,
        VariableProduct $product
    ): Shopware6Product {
        foreach ($product->getBindings() as $bindingId) {
            if ($this->propertyGroupRepository->exists($channel->getId(), $bindingId)) {
                $this->mapOptions($channel, $shopware6Product, $bindingId);
            }
        }

        return $shopware6Product;
    }

    private function mapOptions(
        Shopware6Channel $channel,
        Shopware6Product $shopware6Product,
        AttributeId $bindingId
    ): Shopware6Product {
        $options = $this->optionQuery->getOptions($bindingId);
        foreach ($options as $option) {
            $optionId = new AggregateId($option);
            $shopwareId = $this->optionMap($channel, $bindingId, $optionId);
            if ($shopwareId) {
                $shopware6Product->addConfiguratorSettings(
                    new Shopware6ProductConfiguratorSettings(null, $shopwareId)
                );
            }
        }

        return $shopware6Product;
    }
}
