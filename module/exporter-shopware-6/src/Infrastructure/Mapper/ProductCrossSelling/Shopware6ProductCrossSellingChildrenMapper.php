<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\ProductCrossSelling;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6ProductRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Shopware6ProductCrossSellingMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractShopware6ProductCrossSelling;
use Ergonode\ExporterShopware6\Infrastructure\Model\Basic\Shopware6ProductCrossSellingAssigned;
use Ergonode\ProductCollection\Domain\Entity\ProductCollection;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionElement;

class Shopware6ProductCrossSellingChildrenMapper implements Shopware6ProductCrossSellingMapperInterface
{
    private Shopware6ProductRepositoryInterface $shopware6ProductRepository;

    public function __construct(Shopware6ProductRepositoryInterface $shopware6ProductRepository)
    {
        $this->shopware6ProductRepository = $shopware6ProductRepository;
    }

    public function map(
        Shopware6Channel $channel,
        Export $export,
        AbstractShopware6ProductCrossSelling $shopware6ProductCrossSelling,
        ProductCollection $productCollection,
        ProductCollectionElement $collectionElement,
        ?Language $language = null
    ): AbstractShopware6ProductCrossSelling {
        $position = $this->getStartPosition($shopware6ProductCrossSelling);
        foreach ($productCollection->getElements() as $element) {
            if ($element === $collectionElement) {
                continue;
            }

            $shopware6ProductCrossSelling = $this->mapElement(
                $channel,
                $shopware6ProductCrossSelling,
                $element,
                $position++
            );
        }

        return $shopware6ProductCrossSelling;
    }

    private function mapElement(
        Shopware6Channel $channel,
        AbstractShopware6ProductCrossSelling $shopware6ProductCrossSelling,
        ProductCollectionElement $collectionElement,
        int $position = 1
    ): AbstractShopware6ProductCrossSelling {
        $shopwareId = $this->shopware6ProductRepository->load($channel->getId(), $collectionElement->getProductId());
        if ($shopwareId) {
            $element = new Shopware6ProductCrossSellingAssigned(
                null,
                $shopwareId,
                $position
            );
            $shopware6ProductCrossSelling->addAssignedProducts($element);
        }

        return $shopware6ProductCrossSelling;
    }

    private function getStartPosition(AbstractShopware6ProductCrossSelling $shopware6ProductCrossSelling): int
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
