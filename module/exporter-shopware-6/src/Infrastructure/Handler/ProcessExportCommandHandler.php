<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Handler;

use Ergonode\Exporter\Domain\Repository\ExportRepositoryInterface;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\Exporter\Domain\Command\Export\ProcessExportCommand;
use Ergonode\ExporterShopware6\Infrastructure\Processor\Shopware6ExportStepProcessInterface;
use Webmozart\Assert\Assert;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\ExporterShopware6\Domain\Command\Export\StartShopware6ExportCommand;
use Ergonode\ExporterShopware6\Domain\Command\Export\EndShopware6ExportCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;

/**
 */
class ProcessExportCommandHandler
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
     * @var Shopware6ExportStepProcessInterface[]
     */
    private array $steps;

    /**
     * @param ChannelRepositoryInterface            $channelRepository
     * @param ExportRepositoryInterface             $exportRepository
     * @param CommandBusInterface                   $commandBus
     * @param Shopware6ExportStepProcessInterface[] $steps
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

    /**
     * @param ProcessExportCommand $command
     */
    public function __invoke(ProcessExportCommand $command)
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
