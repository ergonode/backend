<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Product;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Calculator\AttributeTranslationInheritanceCalculator;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Shopware6ProductMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\CreateShopware6Product;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Product\Domain\Entity\AbstractProduct;

/**
 */
class Shopware6ProductStockMapper implements Shopware6ProductMapperInterface
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
     * @param AttributeRepositoryInterface              $repository
     * @param AttributeTranslationInheritanceCalculator $calculator
     */
    public function __construct(
        AttributeRepositoryInterface $repository,
        AttributeTranslationInheritanceCalculator $calculator
    ) {
        $this->repository = $repository;
        $this->calculator = $calculator;
    }

    /**
     * @param Shopware6Product|CreateShopware6Product $shopware6Product
     * @param AbstractProduct                         $product
     * @param Shopware6Channel                        $channel
     *
     * @return Shopware6Product
     */
    public function map(
        Shopware6Product $shopware6Product,
        AbstractProduct $product,
        Shopware6Channel $channel
    ): Shopware6Product {
        if ($shopware6Product instanceof CreateShopware6Product) {
            $attribute = $this->repository->load($channel->getProductStock());
            if (false === $product->hasAttribute($attribute->getCode())) {
                return $shopware6Product;
            }

            $value = $product->getAttribute($attribute->getCode());
            $calculateValue = $this->calculator->calculate($attribute, $value, $channel->getDefaultLanguage());
            if (is_numeric($calculateValue)) {
                $shopware6Product->setStock((int) $calculateValue);
            }
        }

        return $shopware6Product;
    }
}
