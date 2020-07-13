<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Product;

use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\ExporterShopware6\Infrastructure\Exception\Shopware6ExporterMapperException;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Shopware6ProductMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Product\Domain\Entity\AbstractProduct;

/**
 */
class Shopware6ProductSkuMapper implements Shopware6ProductMapperInterface
{
    /**
     * @param Shopware6Product          $shopware6Product
     * @param AbstractProduct           $product
     * @param Shopware6ExportApiProfile $profile
     *
     * @return Shopware6Product
     *
     * @throws Shopware6ExporterMapperException
     */
    public function map(
        Shopware6Product $shopware6Product,
        AbstractProduct $product,
        Shopware6ExportApiProfile $profile
    ): Shopware6Product {

        $shopware6Product->setSku($product->getSku()->getValue());

        return $shopware6Product;
    }
}
