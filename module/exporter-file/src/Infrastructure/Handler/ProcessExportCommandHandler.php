<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Handler;

use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\ExporterFile\Infrastructure\Processor\Step\ExportStepProcessInterface;
use Ergonode\Channel\Domain\Command\Export\ProcessExportCommand;
use Webmozart\Assert\Assert;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\ExporterFile\Domain\Command\Export\StartFileExportCommand;
use Ergonode\ExporterFile\Domain\Command\Export\EndFileExportCommand;

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
        if ($channel instanceof FileExportChannel) {
            $this->commandBus->dispatch(new StartFileExportCommand($export->getId()), true);
            foreach ($this->steps as $step) {
                $step->export($export->getId(), $channel);
            }
            $this->commandBus->dispatch(new EndFileExportCommand($export->getId()), true);
        }
    }
}
