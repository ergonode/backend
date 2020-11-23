<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Handler\Export;

use Ergonode\Exporter\Domain\Repository\ExportRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Infrastructure\Processor\Process\EndShopware6ExportProcess;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Command\Export\EndShopware6ExportCommand;

class EndShopware6ExportCommandHandler
{
    private ExportRepositoryInterface $exportRepository;

    private ChannelRepositoryInterface $channelRepository;

    private EndShopware6ExportProcess $process;

    public function __construct(
        ExportRepositoryInterface $exportRepository,
        ChannelRepositoryInterface $channelRepository,
        EndShopware6ExportProcess $process
    ) {
        $this->exportRepository = $exportRepository;
        $this->channelRepository = $channelRepository;
        $this->process = $process;
    }

    public function __invoke(EndShopware6ExportCommand $command): void
    {
        $export  = $this->exportRepository->load($command->getExportId());
        Assert::isInstanceOf($export, Export::class);
        $channel = $this->channelRepository->load($export->getChannelId());
        Assert::isInstanceOf($channel, Shopware6Channel::class);

        $this->process->process($export, $channel);
        $export->end();
        $this->exportRepository->save($export);
    }
}
