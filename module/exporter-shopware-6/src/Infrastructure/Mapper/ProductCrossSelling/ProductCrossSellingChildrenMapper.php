<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\ProductCrossSelling;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Repository\ProductRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\ProductCrossSellingMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractProductCrossSelling;
use Ergonode\ExporterShopware6\Infrastructure\Model\Basic\AssignedProduct;
use Ergonode\ProductCollection\Domain\Entity\ProductCollection;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionElement;

class ProductCrossSellingChildrenMapper implements ProductCrossSellingMapperInterface
{
    private ProductRepositoryInterface $shopware6ProductRepository;

    public function __construct(ProductRepositoryInterface $shopware6ProductRepository)
    {
        $this->shopware6ProductRepository = $shopware6ProductRepository;
    }

    public function map(
        Shopware6Channel $channel,
        Export $export,
        AbstractProductCrossSelling $shopware6ProductCrossSelling,
        ProductCollection $productCollection,
        ProductCollectionElement $collectionElement,
        ?Language $language = null
    ): AbstractProductCrossSelling {

        foreach ($productCollection->getElements() as $element) {
            if ($element === $collectionElement) {
                continue;
            }

            $shopware6ProductCrossSelling = $this->mapElement(
                $channel,
                $shopware6ProductCrossSelling,
                $element,
                $this->getCurrentPosition($shopware6ProductCrossSelling)
            );
        }

        return $shopware6ProductCrossSelling;
    }

    private function mapElement(
        Shopware6Channel $channel,
        AbstractProductCrossSelling $shopware6ProductCrossSelling,
        ProductCollectionElement $collectionElement,
        int $position = 1
    ): AbstractProductCrossSelling {
        $shopwareId = $this->shopware6ProductRepository->load($channel->getId(), $collectionElement->getProductId());
        if ($shopwareId) {
            $element = new AssignedProduct(
                null,
                $shopwareId,
                $position
            );
            $shopware6ProductCrossSelling->addAssignedProduct($element);
        }

        return $shopware6ProductCrossSelling;
    }

    private function getCurrentPosition(AbstractProductCrossSelling $shopware6ProductCrossSelling): int
    {
        $position = 1;
        foreach ($shopware6ProductCrossSelling->getAssignedProducts() as $assigned) {
            if ($assigned->getPosition() > $position) {
                $position = $assigned->getPosition();
            }
        }

        return $position;
    }
}
