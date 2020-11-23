<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Product;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Calculator\AttributeTranslationInheritanceCalculator;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Shopware6ProductMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Webmozart\Assert\Assert;

class Shopware6ProductStockMapper implements Shopware6ProductMapperInterface
{
    private AttributeRepositoryInterface $repository;

    private AttributeTranslationInheritanceCalculator $calculator;

    public function __construct(
        AttributeRepositoryInterface $repository,
        AttributeTranslationInheritanceCalculator $calculator
    ) {
        $this->repository = $repository;
        $this->calculator = $calculator;
    }

    /**
     * {@inheritDoc}
     */
    public function map(
        Shopware6Channel $channel,
        Export $export,
        Shopware6Product $shopware6Product,
        AbstractProduct $product,
        ?Language $language = null
    ): Shopware6Product {
        $attribute = $this->repository->load($channel->getAttributeProductStock());
        Assert::notNull($attribute);
        if (false === $product->hasAttribute($attribute->getCode())) {
            if ($shopware6Product->isNew()) {
                $shopware6Product->setStock(0);
            }

            return $shopware6Product;
        }

        $value = $product->getAttribute($attribute->getCode());
        $calculateValue = $this->calculator->calculate($attribute, $value, $channel->getDefaultLanguage());
        if (is_numeric($calculateValue)) {
            $shopware6Product->setStock((int) $calculateValue);
        }


        return $shopware6Product;
    }
}
