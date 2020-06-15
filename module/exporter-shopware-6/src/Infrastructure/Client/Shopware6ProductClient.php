<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Client;

use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Product\GetProductList;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Product\PatchProductAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Product\PostProductAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Product\Domain\ValueObject\Sku;

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
     * @param AbstractExportProfile $profile
     * @param Sku                   $sku
     *
     * @return Shopware6Product|null
     */
    public function load(AbstractExportProfile $profile, Sku $sku): ?Shopware6Product
    {
        try {
            $query = [
                [
                    'query' => [
                        'type' => 'equals',
                        'field' => 'productNumber',
                        'value' => $sku->getValue(),
                    ],
                ],
            ];
            $action = new GetProductList($query);

            $productList = $this->connector->execute($profile, $action);
            if (is_array($productList)) {
                return $productList[0];
            }

            return null;
        } catch (\Exception $ex) {
            //todo log
            throw $ex;
        }
    }

    /**
     * @param AbstractExportProfile $profile
     * @param Shopware6Product      $product
     *
     * @return array|object|string|null
     */
    public function insert(AbstractExportProfile $profile, Shopware6Product $product)
    {
        $action = new PostProductAction($product);

        return $this->connector->execute($profile, $action);
    }

    /**
     * @param AbstractExportProfile $profile
     * @param Shopware6Product      $product
     *
     * @return array|object|string|null
     */
    public function update(AbstractExportProfile $profile, Shopware6Product $product)
    {
        $action = new PatchProductAction($product);

        return $this->connector->execute($profile, $action);
    }
}
