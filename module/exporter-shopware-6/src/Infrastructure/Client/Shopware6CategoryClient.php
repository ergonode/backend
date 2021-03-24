<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Client;

use Ergonode\Category\Domain\Entity\AbstractCategory;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Category\DeleteCategory;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Category\GetCategory;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Category\PatchCategoryAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Action\Category\PostCategoryAction;
use Ergonode\ExporterShopware6\Infrastructure\Connector\Shopware6Connector;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Category;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Language;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use GuzzleHttp\Exception\ClientException;

class Shopware6CategoryClient
{
    private Shopware6Connector $connector;

    private CategoryRepositoryInterface $repository;

    public function __construct(Shopware6Connector $connector, CategoryRepositoryInterface $repository)
    {
        $this->connector = $connector;
        $this->repository = $repository;
    }

    /**
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

    public function insert(
        Shopware6Channel $channel,
        Shopware6Category $shopwareCategory,
        AbstractCategory $category
    ): ?Shopware6Category {
        $action = new PostCategoryAction($shopwareCategory, true);

        $newShopwareCategory = $this->connector->execute($channel, $action);

        if (!$newShopwareCategory instanceof Shopware6Category) {
            throw new \LogicException(
                sprintf(
                    'Expected an instance of %s. %s received.',
                    Shopware6Category::class,
                    get_debug_type($newShopwareCategory)
                )
            );
        }
        $this->repository->save(
            $channel->getId(),
            $category->getId(),
            $newShopwareCategory->getId()
        );

        return $newShopwareCategory;
    }

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
