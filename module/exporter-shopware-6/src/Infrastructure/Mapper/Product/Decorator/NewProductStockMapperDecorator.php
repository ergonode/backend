<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Product\Decorator;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Product\ProductStockMapper;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\ProductMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Product\Domain\Entity\AbstractProduct;

class NewProductStockMapperDecorator implements ProductMapperInterface
{
    private ProductStockMapper  $productStockMapper;

    public function __construct(ProductStockMapper $productStockMapper)
    {
        $this->productStockMapper = $productStockMapper;
    }

    public function map(
        Shopware6Channel $channel,
        Export $export,
        Shopware6Product $shopware6Product,
        AbstractProduct $product,
        ?Language $language = null
    ): Shopware6Product {
        if ($shopware6Product->isNew()) {
            return $this->productStockMapper->map($channel, $export, $shopware6Product, $product, $language);
        }

        return $shopware6Product;
    }
}
