<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor\Process;

use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Query\ProductCrossSellingQueryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Client\ProductCrossSellingClient;

class ProductCrossSellingRemoveExportProcess
{
    private ProductCrossSellingQueryInterface $productCrossSellingQuery;

    private ProductCrossSellingClient $productCrossSellingClient;

    public function __construct(
        ProductCrossSellingQueryInterface $productCrossSellingQuery,
        ProductCrossSellingClient $productCrossSellingClient
    ) {
        $this->productCrossSellingQuery = $productCrossSellingQuery;
        $this->productCrossSellingClient = $productCrossSellingClient;
    }

    public function process(Export $export, Shopware6Channel $channel): void
    {
        $shopware6ProductCrossSellingIds = $this->productCrossSellingQuery->getOthersCollection(
            $channel->getId(),
            $channel->getCrossSelling()
        );
        foreach ($shopware6ProductCrossSellingIds as $productCrossSellingId) {
            $this->productCrossSellingClient->delete($channel, $productCrossSellingId);
        }
    }
}
