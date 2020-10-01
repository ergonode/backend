<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Client;

use Ergonode\Category\Domain\Entity\AbstractCategory;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6CategoryRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Category\DeleteCategory;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Category\GetCategory;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Category\PatchCategoryAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Category\PostCategoryAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Category;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Language;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use GuzzleHttp\Exception\ClientException;

/**
 */
class Shopware6CategoryClient
{
    /**
     * @var Shopware6Connector
     */
    private Shopware6Connector $connector;

    /**
     * @var Shopware6CategoryRepositoryInterface
     */
    private Shopware6CategoryRepositoryInterface $repository;

    /**
     * @param Shopware6Connector                   $connector
     * @param Shopware6CategoryRepositoryInterface $repository
     */
    public function __construct(Shopware6Connector $connector, Shopware6CategoryRepositoryInterface $repository)
    {
        $this->connector = $connector;
        $this->repository = $repository;
    }

    /**
     * @param Shopware6Channel       $channel
     * @param string                 $shopwareId
     * @param Shopware6Language|null $shopware6Language
     *
     * @return array|object|string|null
     */
    public function get(Shopware6Channel $channel, string $shopwareId, ?Shopware6Language $shopware6Language = null)
    {
        $action = new GetCategory($shopwareId);
        if ($shopware6Language) {
            $action->addHeader('sw-language-id', $shopware6Language->getId());
        }

        return $this->connector->execute($channel, $action);
    }

    /**
     * @param Shopware6Channel  $channel
     * @param Shopware6Category $shopwareCategory
     * @param AbstractCategory  $category
     *
     * @return Shopware6Category|null
     */
    public function insert(
        Shopware6Channel $channel,
        Shopware6Category $shopwareCategory,
        AbstractCategory $category
    ): ?Shopware6Category {
        $action = new PostCategoryAction($shopwareCategory, true);

        $newShopwareCategory = $this->connector->execute($channel, $action);
        $this->repository->save(
            $channel->getId(),
            $category->getId(),
            $newShopwareCategory->getId()
        );

        return $newShopwareCategory;
    }

    /**
     * @param Shopware6Channel       $channel
     * @param Shopware6Category      $shopwareCategory
     * @param Shopware6Language|null $shopware6Language
     */
    public function update(
        Shopware6Channel $channel,
        Shopware6Category $shopwareCategory,
        ?Shopware6Language $shopware6Language = null
    ): void {
        $action = new PatchCategoryAction($shopwareCategory);
        if ($shopware6Language) {
            $action->addHeader('sw-language-id', $shopware6Language->getId());
        }
        $this->connector->execute($channel, $action);
    }

    /**
     * @param Shopware6Channel $channel
     * @param string           $shopwareId
     * @param CategoryId       $categoryId
     */
    public function delete(Shopware6Channel $channel, string $shopwareId, CategoryId $categoryId): void
    {
        try {
            $action = new DeleteCategory($shopwareId);
            $this->connector->execute($channel, $action);
        } catch (ClientException $exception) {
        }
        $this->repository->delete($channel->getId(), $categoryId);
    }
}
