<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor\Process;

use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6CategoryRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6CategoryClient;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

/**
 */
class CategoryRemoveShopware6ExportProcess
{
    /**
     * @var Shopware6CategoryRepositoryInterface
     */
    private Shopware6CategoryRepositoryInterface $shopwareCategoryRepository;

    /**
     * @var Shopware6CategoryClient
     */
    private Shopware6CategoryClient $categoryClient;

    /**
     * @param Shopware6CategoryRepositoryInterface $shopwareCategoryRepository
     * @param Shopware6CategoryClient              $categoryClient
     */
    public function __construct(
        Shopware6CategoryRepositoryInterface $shopwareCategoryRepository,
        Shopware6CategoryClient $categoryClient
    ) {
        $this->shopwareCategoryRepository = $shopwareCategoryRepository;
        $this->categoryClient = $categoryClient;
    }

    /**
     * @param ExportId         $exportId
     * @param Shopware6Channel $channel
     * @param CategoryId       $categoryId
     */
    public function process(ExportId $exportId, Shopware6Channel $channel, CategoryId $categoryId):void
    {
        $shopwareId = $this->shopwareCategoryRepository->load($channel->getId(), $categoryId);
        if ($shopwareId) {
            $this->categoryClient->delete($channel, $shopwareId, $categoryId);
        }
    }
}
