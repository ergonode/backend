<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Client;

use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6ProductCrossSellingRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\ProductCrossSelling\GetAssignedProductsAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\ProductCrossSelling\GetCrossSellingAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\ProductCrossSelling\PatchCrossSellingAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\ProductCrossSelling\PostCrossSellingAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\ExporterShopware6\Infrastructure\Exception\Shopware6InstanceOfException;
use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractProductCrossSelling;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Language;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class ProductCrossSellingClient
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
    ): ?AbstractProductCrossSelling {

        $action = new GetCrossSellingAction($shopwareId);
        if ($shopware6Language) {
            $action->addHeader('sw-language-id', $shopware6Language->getId());
        }
        $shopwareProductCrossSelling = $this->connector->execute($channel, $action);
        if (!$shopwareProductCrossSelling instanceof AbstractProductCrossSelling) {
            return null;
        }
        $shopwareProductCrossSelling->setAssignedProducts(
            $this->loadAssignedProducts($channel, $shopwareProductCrossSelling->getId())
        );

        return $shopwareProductCrossSelling;
    }

    public function insert(
        Shopware6Channel $channel,
        AbstractProductCrossSelling $productCrossSelling,
        ProductCollectionId $productCollectionId,
        ProductId $productId
    ): ?AbstractProductCrossSelling {
        $action = new PostCrossSellingAction($productCrossSelling, true);

        $shopwareProductCrossSelling = $this->connector->execute($channel, $action);
        if (!$shopwareProductCrossSelling instanceof AbstractProductCrossSelling) {
            throw new Shopware6InstanceOfException(AbstractProductCrossSelling::class);
        }

        $this->productCrossSellingRepository->save(
            $channel->getId(),
            $productCollectionId,
            $productId,
            $shopwareProductCrossSelling->getId()
        );

        return $shopwareProductCrossSelling;
    }

    public function update(
        Shopware6Channel $channel,
        AbstractProductCrossSelling $productCrossSelling,
        ProductCollectionId $productCollectionId,
        ProductId $productId,
        ?Shopware6Language $shopware6Language = null
    ): void {
        $action = new PatchCrossSellingAction($productCrossSelling);
        if ($shopware6Language) {
            $action->addHeader('sw-language-id', $shopware6Language->getId());
        }
        $this->connector->execute($channel, $action);
    }

    private function loadAssignedProducts(Shopware6Channel $channel, string $shopwareId): ?array
    {
        $action = new GetAssignedProductsAction($shopwareId);

        return $this->connector->execute($channel, $action);
    }
}