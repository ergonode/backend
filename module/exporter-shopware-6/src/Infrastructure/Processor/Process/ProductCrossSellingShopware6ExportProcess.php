<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor\Process;

use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Builder\Shopware6ProductCrossSellingBuilder;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6ProductCrossSellingClient;
use Ergonode\ExporterShopware6\Infrastructure\Exception\Shopware6ExporterException;
use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractShopware6ProductCrossSelling;
use Ergonode\ExporterShopware6\Infrastructure\Model\Basic\Shopware6ProductCrossSelling;
use Ergonode\ProductCollection\Domain\Entity\ProductCollection;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionElement;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class ProductCrossSellingShopware6ExportProcess
{
    private Shopware6ProductCrossSellingBuilder $builder;

    private Shopware6ProductCrossSellingClient $productCrossSellingClient;

    public function __construct(
        Shopware6ProductCrossSellingBuilder $builder,
        Shopware6ProductCrossSellingClient $productCrossSellingClient
    ) {
        $this->builder = $builder;
        $this->productCrossSellingClient = $productCrossSellingClient;
    }

    public function process(Export $export, Shopware6Channel $channel, ProductCollection $productCollection): void
    {
        foreach ($productCollection->getElements() as $productCollectionElement) {
            $this->processElement($export, $channel, $productCollection, $productCollectionElement);
        }
    }

    public function processElement(
        Export $export,
        Shopware6Channel $channel,
        ProductCollection $productCollection,
        ProductCollectionElement $collectionElement
    ): void {
        $productCrossSelling = $this->loadProductCrossSelling($channel, $collectionElement->getProductId());
        try {
            if ($productCrossSelling) {
                //todo update implementation
            } else {
                $productCrossSelling = new Shopware6ProductCrossSelling();
                $this->builder->build($channel, $export, $productCrossSelling, $productCollection, $collectionElement);
                $this->productCrossSellingClient->insert(
                    $channel,
                    $productCrossSelling,
                    $collectionElement->getProductId()
                );
            }
        } catch (Shopware6ExporterException $exception) {
            //todo log for user
            throw $exception;
        }
    }

    private function loadProductCrossSelling(
        Shopware6Channel $channel,
        ProductId $productId
    ): ?AbstractShopware6ProductCrossSelling {
        //todo implement method ...
        return null;
    }
}
