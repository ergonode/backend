<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Builder;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Shopware6ProductMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;

/**
 */
class Shopware6ProductBuilder
{
    /**
     * @var Shopware6ProductMapperInterface[]
     */
    private array $collection;

    /**
     * @param Shopware6ProductMapperInterface ...$collection
     */
    public function __construct(Shopware6ProductMapperInterface ...$collection)
    {
        $this->collection = $collection;
    }

    /**
     * @param Shopware6Product $shopware6Product
     * @param AbstractProduct  $product
     * @param Shopware6Channel $channel
     * @param Language|null    $language
     *
     * @return Shopware6Product
     */
    public function build(
        Shopware6Product $shopware6Product,
        AbstractProduct $product,
        Shopware6Channel $channel,
        ?Language $language = null
    ): Shopware6Product {

        foreach ($this->collection as $mapper) {
            $shopware6Product = $mapper->map($shopware6Product, $product, $channel, $language);
        }

        return $shopware6Product;
    }
}
