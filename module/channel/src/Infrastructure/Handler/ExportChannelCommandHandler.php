<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Handler;

use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Channel\Domain\Command\Export\ProcessExportCommand;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\Channel\Domain\Command\ExportChannelCommand;

class ExportChannelCommandHandler
{
    private ChannelRepositoryInterface $channelRepository;

    private ExportRepositoryInterface $exportRepository;

    private CommandBusInterface $commandBus;

    public function __construct(
        ChannelRepositoryInterface $channelRepository,
        ExportRepositoryInterface $exportRepository,
        CommandBusInterface $commandBus
    ) {
        $this->channelRepository = $channelRepository;
        $this->exportRepository = $exportRepository;
        $this->commandBus = $commandBus;
    }

    public function __invoke(ExportChannelCommand $command): void
    {
        $channel = $this->channelRepository->exists($command->getChannelId());
        Assert::true($channel);

        $export = new Export(
            $command->getExportId(),
            $command->getChannelId()
        );

        $this->exportRepository->save($export);

        $this->commandBus->dispatch(new ProcessExportCommand($export->getId()), true);
    }
}
