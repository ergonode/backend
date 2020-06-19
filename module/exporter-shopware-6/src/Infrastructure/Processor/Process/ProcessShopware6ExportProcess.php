<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor\Process;

use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\ExporterShopware6\Infrastructure\Builder\ShopwareProductBuilder;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6ProductClient;
use Ergonode\ExporterShopware6\Infrastructure\Model\CreateShopwareProduct;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

/**
 */
class ProcessShopware6ExportProcess
{
    /**
     * @var ShopwareProductBuilder
     */
    private ShopwareProductBuilder $builder;

    /**
     * @var Shopware6ProductClient
     */
    private Shopware6ProductClient $productClient;

    /**
     * @param ShopwareProductBuilder $builder
     * @param Shopware6ProductClient $productClient
     */
    public function __construct(ShopwareProductBuilder $builder, Shopware6ProductClient $productClient)
    {
        $this->builder = $builder;
        $this->productClient = $productClient;
    }


    /**
     * @param ExportId                                        $id
     * @param AbstractExportProfile|Shopware6ExportApiProfile $profile
     * @param AbstractProduct                                 $product
     */
    public function process(ExportId $id, AbstractExportProfile $profile, AbstractProduct $product): void
    {
        $shopwareProduct = $this->productClient->load($profile, $product->getSku());

        if ($shopwareProduct) {
            $this->builder->build($shopwareProduct, $product, $profile);
            if ($shopwareProduct->isModified()) {
                $this->productClient->update($profile, $shopwareProduct);
            }
        } else {
            $shopwareProduct = new CreateShopwareProduct();
            $this->builder->build($shopwareProduct, $product, $profile);
            $this->productClient->insert($profile, $shopwareProduct);
        }
    }
}
