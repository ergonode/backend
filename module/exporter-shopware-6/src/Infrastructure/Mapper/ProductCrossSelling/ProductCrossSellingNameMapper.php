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
use Ergonode\ExporterShopware6\Infrastructure\Mapper\ProductCrossSellingMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractProductCrossSelling;
use Ergonode\ProductCollection\Domain\Entity\ProductCollection;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionElement;

class ProductCrossSellingNameMapper implements ProductCrossSellingMapperInterface
{
    public function map(
        Shopware6Channel $channel,
        Export $export,
        AbstractProductCrossSelling $shopware6ProductCrossSelling,
        ProductCollection $productCollection,
        ProductCollectionElement $collectionElement,
        ?Language $language = null
    ): AbstractProductCrossSelling {
        $name = $productCollection->getName()->get($language ?: $channel->getDefaultLanguage());
        if ($name) {
            $shopware6ProductCrossSelling->setName($name);
        }

        if (null === $language && null === $name) {
            $shopware6ProductCrossSelling->setName($productCollection->getCode()->getValue());
        }

        return $shopware6ProductCrossSelling;
    }
}
