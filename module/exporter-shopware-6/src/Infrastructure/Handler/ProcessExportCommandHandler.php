<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Handler;

use Ergonode\Channel\Domain\Command\Export\ProcessExportCommand;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Command\Export\EndShopware6ExportCommand;
use Ergonode\ExporterShopware6\Domain\Command\Export\StartShopware6ExportCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Processor\ExportStepProcessInterface;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Webmozart\Assert\Assert;

class ProcessExportCommandHandler
{
    private ChannelRepositoryInterface $channelRepository;

    private ExportRepositoryInterface $exportRepository;

    private CommandBusInterface $commandBus;

    /**
     * @var ExportStepProcessInterface[]
     */
    private array $steps;

    /**
     * @param ExportStepProcessInterface[] $steps
     */
    public function __construct(
        ChannelRepositoryInterface $channelRepository,
        ExportRepositoryInterface $exportRepository,
        CommandBusInterface $commandBus,
        array $steps
    ) {
        $this->channelRepository = $channelRepository;
        $this->exportRepository = $exportRepository;
        $this->commandBus = $commandBus;
        $this->steps = $steps;
    }

    public function __invoke(ProcessExportCommand $command): void
    {
        $export = $this->exportRepository->load($command->getExportId());
        Assert::isInstanceOf($export, Export::class);
        $channel = $this->channelRepository->load($export->getChannelId());
        if ($channel instanceof Shopware6Channel) {
            $this->commandBus->dispatch(new StartShopware6ExportCommand($export->getId()), true);
            foreach ($this->steps as $step) {
                $step->export($export->getId(), $channel);
            }
            $this->commandBus->dispatch(new EndShopware6ExportCommand($export->getId()), true);
        }
    }
}
