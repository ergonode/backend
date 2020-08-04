<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Client;

use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Product\GetProductList;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Product\PatchProductAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Product\PostProductAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6QueryBuilder;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;

/**
 */
class Shopware6ProductClient
{
    /**
     * @var Shopware6Connector
     */
    private Shopware6Connector $connector;

    /**
     * @param Shopware6Connector $connector
     */
    public function __construct(Shopware6Connector $connector)
    {
        $this->connector = $connector;
    }

    /**
     * @param Shopware6Channel $channel
     * @param Sku              $sku
     *
     * @return Shopware6Product|null
     */
    public function findBySKU(Shopware6Channel $channel, Sku $sku): ?Shopware6Product
    {
        try {
            $query = new Shopware6QueryBuilder();
            $query
                ->equals('productNumber', $sku->getValue())
                ->limit(1);

            $action = new GetProductList($query);

            $productList = $this->connector->execute($channel, $action);

            if (is_array($productList) && count($productList) > 0) {
                return $productList[0];
            }

            return null;
        } catch (\Exception $ex) {
            //todo log
            throw $ex;
        }
    }

    /**
     * @param Shopware6Channel $channel
     * @param Shopware6Product $product
     *
     * @return array|object|string|null
     */
    public function insert(Shopware6Channel $channel, Shopware6Product $product)
    {
        $action = new PostProductAction($product);

        return $this->connector->execute($channel, $action);
    }

    /**
     * @param Shopware6Channel $channel
     * @param Shopware6Product $product
     *
     * @return array|object|string|null
     */
    public function update(Shopware6Channel $channel, Shopware6Product $product)
    {
        $action = new PatchProductAction($product);

        return $this->connector->execute($channel, $action);
    }
}
