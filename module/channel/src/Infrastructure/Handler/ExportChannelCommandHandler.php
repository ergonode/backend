<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Infrastructure\Handler;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\Exporter\Domain\Repository\ExportRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Exporter\Domain\Command\Export\ProcessExportCommand;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\Channel\Domain\Command\ExportChannelCommand;

class ExportChannelCommandHandler
{
    private ChannelRepositoryInterface $channelRepository;

    private ExportRepositoryInterface $exportRepository;

    private ProductQueryInterface $productQuery;

    private CommandBusInterface $commandBus;

    public function __construct(
        ChannelRepositoryInterface $channelRepository,
        ExportRepositoryInterface $exportRepository,
        ProductQueryInterface $productQuery,
        CommandBusInterface $commandBus
    ) {
        $this->channelRepository = $channelRepository;
        $this->exportRepository = $exportRepository;
        $this->productQuery = $productQuery;
        $this->commandBus = $commandBus;
    }

    public function __invoke(ExportChannelCommand $command)
    {
        $channel = $this->channelRepository->exists($command->getChannelId());
        Assert::true($channel);

        $ids = $this->productQuery->getAllIds();

        $export = new Export(
            $command->getExportId(),
            $command->getChannelId(),
            count($ids),
        );

        $this->exportRepository->save($export);

        $this->commandBus->dispatch(new ProcessExportCommand($export->getId()), true);
    }
}
