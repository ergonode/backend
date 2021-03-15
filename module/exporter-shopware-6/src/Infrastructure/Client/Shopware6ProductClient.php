<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Client;

use Ergonode\ExporterShopware6\Domain\Query\CategoryQueryInterface;
use Ergonode\ExporterShopware6\Domain\Repository\ProductRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Product\Category\DeleteProductCategory;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Product\Category\GetProductCategory;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Product\ConfiguratorSettings\GetConfiguratorSettings;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Product\GetProductList;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Product\Media\DeleteProductMedia;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Product\Media\GetProductMedia;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Product\PatchProductAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Product\PostProductAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Product\Properties\DeleteProperties;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6QueryBuilder;
use Ergonode\ExporterShopware6\Infrastructure\Model\Product\Shopware6ProductCategory;
use Ergonode\ExporterShopware6\Infrastructure\Model\Product\Shopware6ProductMedia;
use Ergonode\ExporterShopware6\Infrastructure\Model\Product\Shopware6ProductConfiguratorSettings;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Language;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class Shopware6ProductClient
{
    private Shopware6Connector $connector;

    private ProductRepositoryInterface $repository;

    private CategoryQueryInterface $categoryQuery;

    public function __construct(
        Shopware6Connector $connector,
        ProductRepositoryInterface $repository,
        CategoryQueryInterface $categoryQuery
    ) {
        $this->connector = $connector;
        $this->repository = $repository;
        $this->categoryQuery = $categoryQuery;
    }

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

    public function insert(Shopware6Channel $channel, Shopware6Product $product, ProductId $productId): void
    {
        $action = new PostProductAction($product, true);

        $newId = $this->connector->execute($channel, $action);
        $this->repository->save($channel->getId(), $productId, $newId);
    }

    public function update(
        Shopware6Channel $channel,
        Shopware6Product $product,
        ?Shopware6Language $shopware6Language = null
    ): void {
        if ($product->isModified()) {
            $action = new PatchProductAction($product);
            if ($shopware6Language) {
                $action->addHeader('sw-language-id', $shopware6Language->getId());
            }
            $this->connector->execute($channel, $action);
        }
        if ($product->hasItemToRemoved()) {
            $this->removeProperty($channel, $product);
            $this->removeCategory($channel, $product);
            $this->removeMedia($channel, $product);
        }
    }

    private function removeProperty(Shopware6Channel $channel, Shopware6Product $product): void
    {
        foreach ($product->getPropertyToRemove() as $propertyId) {
            $action = new DeleteProperties($product->getId(), $propertyId);
            $this->connector->execute($channel, $action);
        }
    }

    private function removeCategory(Shopware6Channel $channel, Shopware6Product $product): void
    {
        foreach ($product->getCategoryToRemove() as $categoryId) {
            if ($this->categoryQuery->loadByShopwareId($channel->getId(), $categoryId)) {
                $action = new DeleteProductCategory($product->getId(), $categoryId);
                $this->connector->execute($channel, $action);
            }
        }
    }

    private function removeMedia(Shopware6Channel $channel, Shopware6Product $product): void
    {
        foreach ($product->getMediaToRemove() as $media) {
            $action = new DeleteProductMedia($product->getId(), $media->getId());
            $this->connector->execute($channel, $action);
        }
    }

    /**
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
                $product->setCategories($this->loadCategory($channel, $product->getId()));
            }

            return $productList;
        }

        return [];
    }

    /**
     * @return Shopware6ProductConfiguratorSettings[]|null
     */
    private function loadConfiguratorSettings(Shopware6Channel $channel, string $shopwareId): ?array
    {
        $action = new GetConfiguratorSettings($shopwareId);

        return $this->connector->execute($channel, $action);
    }

    /**
     * @return Shopware6ProductMedia[]|null
     */
    private function loadMedia(Shopware6Channel $channel, string $shopwareId): ?array
    {
        $action = new GetProductMedia($shopwareId);

        return $this->connector->execute($channel, $action);
    }

    /**
     * @return Shopware6ProductCategory[] |null
     */
    private function loadCategory(Shopware6Channel $channel, string $shopwareId): ?array
    {
        $action = new GetProductCategory($shopwareId);

        return $this->connector->execute($channel, $action);
    }
}
