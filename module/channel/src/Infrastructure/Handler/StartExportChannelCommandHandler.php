<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Infrastructure\Handler;

use Ergonode\Channel\Domain\Command\StartExportChannelCommand;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Exporter\Domain\Command\Export\StartExportCommand;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\Exporter\Domain\Repository\ExportRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class StartExportChannelCommandHandler
{
    /**
     * @var ChannelRepositoryInterface
     */
    private ChannelRepositoryInterface $channelRepository;

    /**
     * @var ExportRepositoryInterface
     */
    private ExportRepositoryInterface $exportRepository;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param ChannelRepositoryInterface $channelRepository
     * @param ExportRepositoryInterface  $exportRepository
     * @param CommandBusInterface        $commandBus
     */
    public function __construct(
        ChannelRepositoryInterface $channelRepository,
        ExportRepositoryInterface $exportRepository,
        CommandBusInterface $commandBus
    ) {
        $this->channelRepository = $channelRepository;
        $this->exportRepository = $exportRepository;
        $this->commandBus = $commandBus;
    }

    /**
     * @param StartExportChannelCommand $command
     */
    public function __invoke(StartExportChannelCommand $command)
    {
        $channel = $this->channelRepository->load($command->getChannelId());
        Assert::notNull($channel);

        $export = new Export(
            $command->getExportId(),
            $command->getChannelId()
        );

        $this->exportRepository->save($export);

        $this->commandBus->dispatch(
            new StartExportCommand($export->getId())
        );
    }
}
