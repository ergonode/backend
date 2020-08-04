<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor\Process;

use Ergonode\ExporterShopware6\Infrastructure\Builder\Shopware6ProductBuilder;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6ProductClient;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;

/**
 */
class ProcessShopware6ExportProcess
{
    /**
     * @var Shopware6ProductBuilder
     */
    private Shopware6ProductBuilder $builder;

    /**
     * @var Shopware6ProductClient
     */
    private Shopware6ProductClient $productClient;

    /**
     * @param Shopware6ProductBuilder $builder
     * @param Shopware6ProductClient  $productClient
     */
    public function __construct(Shopware6ProductBuilder $builder, Shopware6ProductClient $productClient)
    {
        $this->builder = $builder;
        $this->productClient = $productClient;
    }

    /**
     * @param ExportId         $id
     * @param Shopware6Channel $channel
     * @param AbstractProduct  $product
     */
    public function process(ExportId $id, Shopware6Channel $channel, AbstractProduct $product): void
    {
        $shopwareProduct = $this->productClient->findBySKU($channel, $product->getSku());

        if ($shopwareProduct) {
            $this->builder->build($shopwareProduct, $product, $channel);
            if ($shopwareProduct->isModified()) {
                $this->productClient->update($channel, $shopwareProduct);
            }
        } else {
            $shopwareProduct = new Shopware6Product();
            $this->builder->build($shopwareProduct, $product, $channel);
            $this->productClient->insert($channel, $shopwareProduct);
        }
    }
}
