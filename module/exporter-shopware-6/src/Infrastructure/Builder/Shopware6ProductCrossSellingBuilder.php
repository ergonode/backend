<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Builder;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Shopware6ProductCrossSellingMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractShopware6ProductCrossSelling;
use Ergonode\ProductCollection\Domain\Entity\ProductCollection;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionElement;

class Shopware6ProductCrossSellingBuilder
{
    /**
     * @var Shopware6ProductCrossSellingMapperInterface[]
     */
    private array $collection;

    public function __construct(Shopware6ProductCrossSellingMapperInterface ...$collection)
    {
        $this->collection = $collection;
    }

    public function build(
        Shopware6Channel $channel,
        Export $export,
        AbstractShopware6ProductCrossSelling $shopware6ProductCrossSelling,
        ProductCollection $productCollection,
        ProductCollectionElement $collectionElement,
        ?Language $language = null
    ): AbstractShopware6ProductCrossSelling {
        foreach ($this->collection as $mapper) {
            $shopware6ProductCrossSelling = $mapper->map(
                $channel,
                $export,
                $shopware6ProductCrossSelling,
                $productCollection,
                $collectionElement,
                $language
            );
        }

        return $shopware6ProductCrossSelling;
    }
}
