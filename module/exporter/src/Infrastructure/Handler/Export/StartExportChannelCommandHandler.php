<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\Handler\Export;

use Ergonode\Exporter\Domain\Command\Export\StartChannelExportCommand;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Exporter\Domain\Command\Export\StartExportCommand;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\Exporter\Domain\Repository\ChannelConfigurationRepositoryInterface;
use Ergonode\Exporter\Domain\Repository\ExportProfileRepositoryInterface;
use Ergonode\Exporter\Domain\Repository\ExportRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class StartExportChannelCommandHandler
{
    /**
     * @var ChannelConfigurationRepositoryInterface
     */
    private ChannelConfigurationRepositoryInterface $channelConfigurationRepository;

    /**
     * @var ExportProfileRepositoryInterface
     */
    private ExportProfileRepositoryInterface $exportProfileRepository;

    /**
     * @var ExportRepositoryInterface
     */
    private ExportRepositoryInterface $exportRepository;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param ChannelConfigurationRepositoryInterface $channelConfigurationRepository
     * @param ExportProfileRepositoryInterface        $exportProfileRepository
     * @param ExportRepositoryInterface               $exportRepository
     * @param CommandBusInterface                     $commandBus
     */
    public function __construct(
        ChannelConfigurationRepositoryInterface $channelConfigurationRepository,
        ExportProfileRepositoryInterface $exportProfileRepository,
        ExportRepositoryInterface $exportRepository,
        CommandBusInterface $commandBus
    ) {
        $this->channelConfigurationRepository = $channelConfigurationRepository;
        $this->exportProfileRepository = $exportProfileRepository;
        $this->exportRepository = $exportRepository;
        $this->commandBus = $commandBus;
    }


    /**
     * @param StartChannelExportCommand $command
     */
    public function __invoke(StartChannelExportCommand $command)
    {
        $channelConfiguration = $this->channelConfigurationRepository->exists($command->getChannelId());
        Assert::true($channelConfiguration);
        $exportProfile = $this->exportProfileRepository->exists($command->getExportProfileId());
        Assert::true($exportProfile);

        $export = new Export(
            $command->getExportId(),
            $command->getChannelId(),
            $command->getExportProfileId()
        );

        $this->exportRepository->save($export);

        $this->commandBus->dispatch(
            new StartExportCommand($export->getId())
        );
    }
}
