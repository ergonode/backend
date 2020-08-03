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
use Webmozart\Assert\Assert;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\ExporterShopware6\Domain\Command\Export\StartShopware6ExportCommand;
use Ergonode\ExporterShopware6\Domain\Command\Export\EndShopware6ExportCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\ExporterShopware6\Domain\Command\Export\ProcessShopware6ExportCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

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

    private ProductQueryInterface $query;

    /**
     * @param ChannelRepositoryInterface $channelRepository
     * @param ExportRepositoryInterface  $exportRepository
     * @param CommandBusInterface        $commandBus
     * @param ProductQueryInterface      $query
     */
    public function __construct(
        ChannelRepositoryInterface $channelRepository,
        ExportRepositoryInterface $exportRepository,
        CommandBusInterface $commandBus,
        ProductQueryInterface $query
    ) {
        $this->channelRepository = $channelRepository;
        $this->exportRepository = $exportRepository;
        $this->commandBus = $commandBus;
        $this->query = $query;
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
            foreach ($this->query->getAllIds() as $product) {
                $productId = new ProductId($product);
                $processCommand = new ProcessShopware6ExportCommand($export->getId(), $productId);
                $this->commandBus->dispatch($processCommand, true);
            }
            $this->commandBus->dispatch(new EndShopware6ExportCommand($export->getId()), true);
        }
    }
}
