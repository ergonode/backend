<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Handler\Export;

use Ergonode\Category\Domain\Entity\AbstractCategory;
use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\Exporter\Domain\Repository\ExportRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Command\Export\CategoryShopware6ExportCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Processor\Process\CategoryShopware6ExportProcess;
use Webmozart\Assert\Assert;

class CategoryShopware6ExportCommandHandler
{
    private ExportRepositoryInterface $exportRepository;

    private ChannelRepositoryInterface $channelRepository;

    private CategoryRepositoryInterface $categoryRepository;

    private CategoryShopware6ExportProcess $process;

    public function __construct(
        ExportRepositoryInterface $exportRepository,
        ChannelRepositoryInterface $channelRepository,
        CategoryRepositoryInterface $categoryRepository,
        CategoryShopware6ExportProcess $process
    ) {
        $this->exportRepository = $exportRepository;
        $this->channelRepository = $channelRepository;
        $this->categoryRepository = $categoryRepository;
        $this->process = $process;
    }

    public function __invoke(CategoryShopware6ExportCommand $command): void
    {
        $export = $this->exportRepository->load($command->getExportId());
        Assert::isInstanceOf($export, Export::class);
        $channel = $this->channelRepository->load($export->getChannelId());
        Assert::isInstanceOf($channel, Shopware6Channel::class);
        $category = $this->categoryRepository->load($command->getCategoryId());
        Assert::isInstanceOf($category, AbstractCategory::class);

        $this->process->process($export, $channel, $category, $command->getParentCategoryId());
    }
}
