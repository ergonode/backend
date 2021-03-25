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
use Ergonode\ExporterShopware6\Domain\Repository\ProductCrossSellingRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Repository\ProductRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Client\ProductCrossSellingClient;
use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractProductCrossSelling;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Language;
use Ergonode\ProductCollection\Domain\Entity\ProductCollection;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use GuzzleHttp\Exception\ClientException;

class ProductCrossSellingRemoveProductExportProcess
{
    private ProductCrossSellingClient $productCrossSellingClient;

    private ProductCrossSellingRepositoryInterface $productCrossSellingRepository;

    private ProductCrossSellingQueryInterface $productCrossSellingQuery;

    private ProductRepositoryInterface $productRepository;

    public function __construct(
        ProductCrossSellingClient $productCrossSellingClient,
        ProductCrossSellingRepositoryInterface $productCrossSellingRepository,
        ProductCrossSellingQueryInterface $productCrossSellingQuery,
        ProductRepositoryInterface $productRepository
    ) {
        $this->productCrossSellingClient = $productCrossSellingClient;
        $this->productCrossSellingRepository = $productCrossSellingRepository;
        $this->productCrossSellingQuery = $productCrossSellingQuery;
        $this->productRepository = $productRepository;
    }


    public function process(Export $export, Shopware6Channel $channel, ProductCollection $productCollection): void
    {
        $shopwareRemoveIds = $this->loadDeleteElement($productCollection, $channel);
        if (!empty($shopwareRemoveIds)) {
            foreach ($shopwareRemoveIds as $toRemove) {
                $deleteProductId = new ProductId($toRemove['product_id']);

                foreach ($productCollection->getElements() as $collectionElement) {
                    $this->deleteProcess(
                        $channel,
                        $productCollection->getId(),
                        $collectionElement->getProductId(),
                        $deleteProductId
                    );
                }
                $this->productCrossSellingClient->delete($channel, $toRemove['shopware6_id']);
            }
        }
    }

    private function deleteProcess(
        Shopware6Channel $channel,
        ProductCollectionId $productCollectionId,
        ProductId $productId,
        ProductId $deleteProductId
    ): void {
        $productCrossSelling = $this->loadProductCrossSelling($channel, $productCollectionId, $productId);
        if ($productCrossSelling) {
            foreach ($productCrossSelling->getAssignedProducts() as $assignedProduct) {
                $shopwareProductId = $this->productRepository->load($channel->getId(), $deleteProductId);
                if ($assignedProduct->getProductId() === $shopwareProductId) {
                    $this->productCrossSellingClient->deleteAssignedProducts(
                        $channel,
                        $productCrossSelling->getId(),
                        $assignedProduct->getId()
                    );
                }
            }
        }
    }

    private function loadProductCrossSelling(
        Shopware6Channel $channel,
        ProductCollectionId $productCollectionId,
        ProductId $productId,
        ?Shopware6Language $shopware6Language = null
    ): ?AbstractProductCrossSelling {
        $shopwareId = $this->productCrossSellingRepository->load($channel->getId(), $productCollectionId, $productId);
        if ($shopwareId) {
            try {
                return $this->productCrossSellingClient->get($channel, $shopwareId, $shopware6Language);
            } catch (ClientException $exception) {
            }
        }

        return null;
    }

    private function loadDeleteElement(ProductCollection $productCollection, Shopware6Channel $channel): array
    {
        $productIds = [];
        foreach ($productCollection->getElements() as $collectionElement) {
            $productIds[] = $collectionElement->getProductId();
        }

        return $this->productCrossSellingQuery->getOthersElements(
            $channel->getId(),
            $productCollection->getId(),
            $productIds
        );
    }
}
