<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Handler\Export;

use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\Exporter\Domain\Repository\ExportRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Command\Export\CategoryRemoveShopware6ExportCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Processor\Process\CategoryRemoveShopware6ExportProcess;
use Webmozart\Assert\Assert;

class CategoryRemoveShopware6ExportCommandHandler
{
    private ExportRepositoryInterface $exportRepository;

    private ChannelRepositoryInterface $channelRepository;

    private CategoryRemoveShopware6ExportProcess $process;

    public function __construct(
        ExportRepositoryInterface $exportRepository,
        ChannelRepositoryInterface $channelRepository,
        CategoryRemoveShopware6ExportProcess $process
    ) {
        $this->exportRepository = $exportRepository;
        $this->channelRepository = $channelRepository;
        $this->process = $process;
    }

    public function __invoke(CategoryRemoveShopware6ExportCommand $command): void
    {
        $export = $this->exportRepository->load($command->getExportId());
        Assert::isInstanceOf($export, Export::class);
        $channel = $this->channelRepository->load($export->getChannelId());
        Assert::isInstanceOf($channel, Shopware6Channel::class);

        $this->process->process($export, $channel, $command->getCategoryId());
    }
}
