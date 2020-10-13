<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Product\Decorator;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Product\Shopware6ProductActiveMapper;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Shopware6ProductMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Product\Domain\Entity\AbstractProduct;

/**
 */
class Shopware6NewProductActiveMapperDecorator implements Shopware6ProductMapperInterface
{
    /**
     * @var Shopware6ProductActiveMapper
     */
    private Shopware6ProductActiveMapper $productActiveMapper;

    /**
     * @param Shopware6ProductActiveMapper $productActiveMapper
     */
    public function __construct(Shopware6ProductActiveMapper $productActiveMapper)
    {
        $this->productActiveMapper = $productActiveMapper;
    }

    /**
     * @param Shopware6Product $shopware6Product
     * @param AbstractProduct  $product
     * @param Shopware6Channel $channel
     * @param Language|null    $language
     *
     * @return Shopware6Product
     */
    public function map(
        Shopware6Product $shopware6Product,
        AbstractProduct $product,
        Shopware6Channel $channel,
        ?Language $language = null
    ): Shopware6Product {
        if ($shopware6Product->isNew()) {
            return $this->productActiveMapper->map($shopware6Product, $product, $channel, $language);
        }

        return $shopware6Product;
    }
}
