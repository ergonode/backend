<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Client;

use Ergonode\ExporterShopware6\Domain\Repository\Shopware6ProductRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Product\ConfiguratorSettings\GetConfiguratorSettings;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Product\GetProductList;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Product\Media\GetProductMedia;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Product\PatchProductAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Product\PostProductAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Product\Properties\DeleteProperties;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6QueryBuilder;
use Ergonode\ExporterShopware6\Infrastructure\Model\Product\Shopware6ProductMedia;
use Ergonode\ExporterShopware6\Infrastructure\Model\Product\Shopware6ProductConfiguratorSettings;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Language;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

/**
 */
class Shopware6ProductClient
{
    /**
     * @var Shopware6Connector
     */
    private Shopware6Connector $connector;

    /**
     * @var Shopware6ProductRepositoryInterface
     */
    private Shopware6ProductRepositoryInterface $repository;

    /**
     * @param Shopware6Connector                  $connector
     * @param Shopware6ProductRepositoryInterface $repository
     */
    public function __construct(Shopware6Connector $connector, Shopware6ProductRepositoryInterface $repository)
    {
        $this->connector = $connector;
        $this->repository = $repository;
    }

    /**
     * @param Shopware6Channel       $channel
     * @param AbstractProduct        $product
     * @param Shopware6Language|null $shopware6Language
     *
     * @return Shopware6Product|null
     */
    public function find(
        Shopware6Channel $channel,
        AbstractProduct $product,
        ?Shopware6Language $shopware6Language = null
    ): ?Shopware6Product {

        $query = new Shopware6QueryBuilder();
        $query
            ->equals('productNumber', $product->getSku()->getValue())
            ->limit(1);

        $action = new GetProductList($query);

        if ($shopware6Language) {
            $action->addHeader('sw-language-id', $shopware6Language->getId());
        }
        $productList = $this->load($channel, $action);

        if (count($productList) > 0) {
            $shopwareProduct = reset($productList);

            $this->repository->save($channel->getId(), $product->getId(), $shopwareProduct->getId());

            return $shopwareProduct;
        }

        return null;
    }

    /**
     * @param Shopware6Channel $channel
     * @param Shopware6Product $product
     * @param ProductId        $productId
     */
    public function insert(Shopware6Channel $channel, Shopware6Product $product, ProductId $productId): void
    {
        $action = new PostProductAction($product, true);

        $newId = $this->connector->execute($channel, $action);
        $this->repository->save($channel->getId(), $productId, $newId);
    }

    /**
     * @param Shopware6Channel       $channel
     * @param Shopware6Product       $product
     * @param Shopware6Language|null $shopware6Language
     */
    public function update(
        Shopware6Channel $channel,
        Shopware6Product $product,
        ?Shopware6Language $shopware6Language = null
    ): void {
        $action = new PatchProductAction($product);
        if ($shopware6Language) {
            $action->addHeader('sw-language-id', $shopware6Language->getId());
        }
        $this->connector->execute($channel, $action);

        $this->removeProperty($channel, $product);
    }

    /**
     * @param Shopware6Channel $channel
     * @param Shopware6Product $product
     */
    private function removeProperty(Shopware6Channel $channel, Shopware6Product $product): void
    {
        foreach ($product->getPropertyToRemove() as $propertyId) {
            $action = new DeleteProperties($product->getId(), $propertyId);
            $this->connector->execute($channel, $action);
        }
    }

    /**
     * @param Shopware6Channel $channel
     * @param GetProductList   $getAction
     *
     * @return Shopware6Product[]
     */
    private function load(Shopware6Channel $channel, GetProductList $getAction): array
    {
        $productList = $this->connector->execute($channel, $getAction);
        if (is_array($productList) && count($productList) > 0) {
            /** @var Shopware6Product $product */
            foreach ($productList as $product) {
                $product->setConfiguratorSettings($this->loadConfiguratorSettings($channel, $product->getId()));
                $product->setMedia($this->loadMedia($channel, $product->getId()));
            }

            return $productList;
        }

        return [];
    }

    /**
     * @param Shopware6Channel $channel
     * @param string           $shopwareId
     *
     * @return Shopware6ProductConfiguratorSettings[]|null
     */
    private function loadConfiguratorSettings(Shopware6Channel $channel, string $shopwareId): ?array
    {
        $action = new GetConfiguratorSettings($shopwareId);

        return $this->connector->execute($channel, $action);
    }

    /**
     * @param Shopware6Channel $channel
     * @param string           $shopwareId
     *
     * @return Shopware6ProductMedia[]|null
     */
    private function loadMedia(Shopware6Channel $channel, string $shopwareId): ?array
    {
        $action = new GetProductMedia($shopwareId);

        return $this->connector->execute($channel, $action);
    }
}
