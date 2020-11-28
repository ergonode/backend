<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor\Process;

use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6CategoryRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6CategoryClient;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

class CategoryRemoveShopware6ExportProcess
{
    private Shopware6CategoryRepositoryInterface $shopwareCategoryRepository;

    private Shopware6CategoryClient $categoryClient;

    public function __construct(
        Shopware6CategoryRepositoryInterface $shopwareCategoryRepository,
        Shopware6CategoryClient $categoryClient
    ) {
        $this->shopwareCategoryRepository = $shopwareCategoryRepository;
        $this->categoryClient = $categoryClient;
    }

    public function process(Export $export, Shopware6Channel $channel, CategoryId $categoryId): void
    {
        $shopwareId = $this->shopwareCategoryRepository->load($channel->getId(), $categoryId);
        if ($shopwareId) {
            $this->categoryClient->delete($channel, $shopwareId, $categoryId);
        }
    }
}
