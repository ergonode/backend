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
use Ergonode\ExporterShopware6\Infrastructure\Exception\Mapper\Shopware6ExporterProductNoFoundException;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\ProductCrossSellingMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractProductCrossSelling;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\ProductCollection\Domain\Entity\ProductCollection;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionElement;

class ProductCrossSellingRootProductIdMapper implements ProductCrossSellingMapperInterface
{
    private ProductRepositoryInterface $shopware6ProductRepository;

    private ProductQueryInterface $productQuery;

    public function __construct(
        ProductRepositoryInterface $shopware6ProductRepository,
        ProductQueryInterface $productQuery
    ) {
        $this->shopware6ProductRepository = $shopware6ProductRepository;
        $this->productQuery = $productQuery;
    }

    /**
     * @throws Shopware6ExporterProductNoFoundException
     */
    public function map(
        Shopware6Channel $channel,
        Export $export,
        AbstractProductCrossSelling $shopware6ProductCrossSelling,
        ProductCollection $productCollection,
        ProductCollectionElement $collectionElement,
        ?Language $language = null
    ): AbstractProductCrossSelling {
        $shopwareId = $this->shopware6ProductRepository->load($channel->getId(), $collectionElement->getProductId());
        if (null === $shopwareId) {
            throw new Shopware6ExporterProductNoFoundException(
                $collectionElement->getProductId(),
                $this->productQuery->findSkuByProductId($collectionElement->getProductId())
            );
        }

        $shopware6ProductCrossSelling->setProductId($shopwareId);

        return $shopware6ProductCrossSelling;
    }
}
