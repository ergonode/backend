<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Builder;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\ProductCrossSellingMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractProductCrossSelling;
use Ergonode\ProductCollection\Domain\Entity\ProductCollection;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionElement;

class ProductCrossSellingBuilder
{
    /**
     * @var ProductCrossSellingMapperInterface[]
     */
    private array $collection;

    public function __construct(ProductCrossSellingMapperInterface ...$collection)
    {
        $this->collection = $collection;
    }

    public function build(
        Shopware6Channel $channel,
        Export $export,
        AbstractProductCrossSelling $shopware6ProductCrossSelling,
        ProductCollection $productCollection,
        ProductCollectionElement $collectionElement,
        ?Language $language = null
    ): AbstractProductCrossSelling {
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
