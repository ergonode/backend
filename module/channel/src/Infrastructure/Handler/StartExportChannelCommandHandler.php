<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Infrastructure\Handler;

use Ergonode\Channel\Domain\Command\StartChannelExportCommand;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Exporter\Domain\Command\Export\StartExportCommand;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\Exporter\Domain\Repository\ExportRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Exporter\Domain\Command\Export\ProcessExportCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Exporter\Domain\Command\Export\EndExportCommand;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;

/**
 */
class StartExportChannelCommandHandler
{
    /**
     * @var ChannelRepositoryInterface
     */
    private ChannelRepositoryInterface $chanelRepository;

    /**
     * @var ExportRepositoryInterface
     */
    private ExportRepositoryInterface $exportRepository;

    /**
     * @var ProductQueryInterface
     */
    private ProductQueryInterface $productQuery;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param ChannelRepositoryInterface $chanelRepository
     * @param ExportRepositoryInterface  $exportRepository
     * @param ProductQueryInterface      $productQuery
     * @param CommandBusInterface        $commandBus
     */
    public function __construct(
        ChannelRepositoryInterface $chanelRepository,
        ExportRepositoryInterface $exportRepository,
        ProductQueryInterface $productQuery,
        CommandBusInterface $commandBus
    ) {
        $this->chanelRepository = $chanelRepository;
        $this->exportRepository = $exportRepository;
        $this->productQuery = $productQuery;
        $this->commandBus = $commandBus;
    }

    /**
     * @param StartChannelExportCommand $command
     */
    public function __invoke(StartChannelExportCommand $command)
    {
        $channel = $this->chanelRepository->exists($command->getChannelId());
        Assert::true($channel);

        $ids = $this->productQuery->getAllIds();

        $export = new Export(
            $command->getExportId(),
            $command->getChannelId(),
            count($ids),
        );

        $this->exportRepository->save($export);

        $this->commandBus->dispatch(new StartExportCommand($export->getId()));

        foreach ($ids as $id) {
            $this->commandBus->dispatch(new ProcessExportCommand($export->getId(), new ProductId($id)), true);
        }

        $this->commandBus->dispatch(new EndExportCommand($export->getId()));
    }
}
