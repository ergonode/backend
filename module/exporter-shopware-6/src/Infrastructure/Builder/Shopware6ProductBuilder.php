<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Builder;

use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Shopware6ProductMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Product\Domain\Entity\AbstractProduct;

/**
 */
class Shopware6ProductBuilder
{
    /**
     * @var Shopware6ProductMapperInterface[]
     */
    private array $collection;

    /**
     * @param Shopware6ProductMapperInterface ...$collection
     */
    public function __construct(Shopware6ProductMapperInterface ...$collection)
    {
        $this->collection = $collection;
    }

    /**
     * @param Shopware6Product          $shopware6Product
     * @param AbstractProduct           $product
     * @param Shopware6ExportApiProfile $profile
     *
     * @return Shopware6Product
     */
    public function build(
        Shopware6Product $shopware6Product,
        AbstractProduct $product,
        Shopware6ExportApiProfile $profile
    ): Shopware6Product {

        foreach ($this->collection as $mapper) {
            $shopware6Product = $mapper->map($shopware6Product, $product, $profile);
        }

        return $shopware6Product;
    }
}
