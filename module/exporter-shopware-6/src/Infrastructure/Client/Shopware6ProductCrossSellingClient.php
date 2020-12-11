<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Client;

use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6ProductCrossSellingRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\ProductCrossSelling\AssignedProducts\GetAssignedProductsAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\ProductCrossSelling\GetProductCrossSellingAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\ProductCrossSelling\PostProductCrossSellingAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\ExporterShopware6\Infrastructure\Exception\Shopware6InstanceOfException;
use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractShopware6ProductCrossSelling;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Language;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class Shopware6ProductCrossSellingClient
{
    private Shopware6Connector $connector;

    private Shopware6ProductCrossSellingRepositoryInterface $productCrossSellingRepository;

    public function __construct(
        Shopware6Connector $connector,
        Shopware6ProductCrossSellingRepositoryInterface $productCrossSellingRepository
    ) {
        $this->connector = $connector;
        $this->productCrossSellingRepository = $productCrossSellingRepository;
    }

    public function get(
        Shopware6Channel $channel,
        string $shopwareId,
        ?Shopware6Language $shopware6Language = null
    ): ?AbstractShopware6ProductCrossSelling {

        $action = new GetProductCrossSellingAction($shopwareId);
        if ($shopware6Language) {
            $action->addHeader('sw-language-id', $shopware6Language->getId());
        }
        $shopwareProductCrossSelling = $this->connector->execute($channel, $action);
        if (!$shopwareProductCrossSelling instanceof AbstractShopware6ProductCrossSelling) {
            return null;
        }
        $shopwareProductCrossSelling->setAssignedProducts(
            $this->loadAssignedProducts($channel, $shopwareProductCrossSelling->getId())
        );

        return $shopwareProductCrossSelling;
    }

    public function insert(
        Shopware6Channel $channel,
        AbstractShopware6ProductCrossSelling $productCrossSelling,
        ProductCollectionId $productCollectionId,
        ProductId $productId
    ): ?AbstractShopware6ProductCrossSelling {
        $action = new PostProductCrossSellingAction($productCrossSelling, true);

        $shopwareProductCrossSelling = $this->connector->execute($channel, $action);
        if (!$shopwareProductCrossSelling instanceof AbstractShopware6ProductCrossSelling) {
            throw new Shopware6InstanceOfException(AbstractShopware6ProductCrossSelling::class);
        }

        $this->productCrossSellingRepository->save(
            $channel->getId(),
            $productCollectionId,
            $productId,
            $shopwareProductCrossSelling->getId()
        );

        return $shopwareProductCrossSelling;
    }

    private function loadAssignedProducts(Shopware6Channel $channel, string $shopwareId): ?array
    {
        $action = new GetAssignedProductsAction($shopwareId);

        return $this->connector->execute($channel, $action);
    }
}
