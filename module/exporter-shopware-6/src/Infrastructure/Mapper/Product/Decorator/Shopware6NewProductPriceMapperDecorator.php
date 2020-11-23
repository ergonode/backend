<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Product\Decorator;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Exception\Mapper\Shopware6ExporterNoMapperException;
use Ergonode\ExporterShopware6\Infrastructure\Exception\Mapper\Shopware6ExporterNumericAttributeException;
use Ergonode\ExporterShopware6\Infrastructure\Exception\Mapper\Shopware6ExporterProductAttributeException;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Product\Shopware6ProductPriceMapper;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Shopware6ProductMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Product\Domain\Entity\AbstractProduct;

class Shopware6NewProductPriceMapperDecorator implements Shopware6ProductMapperInterface
{
    private Shopware6ProductPriceMapper  $productPriceMapper;

    public function __construct(Shopware6ProductPriceMapper $productPriceMapper)
    {
        $this->productPriceMapper = $productPriceMapper;
    }

    /**
     * @throws Shopware6ExporterNoMapperException
     * @throws Shopware6ExporterNumericAttributeException
     * @throws Shopware6ExporterProductAttributeException
     */
    public function map(
        Shopware6Channel $channel,
        Export $export,
        Shopware6Product $shopware6Product,
        AbstractProduct $product,
        ?Language $language = null
    ): Shopware6Product {
        if ($shopware6Product->isNew()) {
            return $this->productPriceMapper->map($channel, $export, $shopware6Product, $product, $language);
        }

        return $shopware6Product;
    }
}
