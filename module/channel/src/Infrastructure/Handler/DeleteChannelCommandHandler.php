<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Handler;

use Ergonode\Channel\Domain\Command\DeleteChannelCommand;
use Ergonode\Channel\Domain\Entity\AbstractChannel;
use Ergonode\Channel\Domain\Query\ExportQueryInterface;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Channel\Domain\Command\Export\DeleteExportCommand;
use Webmozart\Assert\Assert;

class DeleteChannelCommandHandler
{
    private ChannelRepositoryInterface $channelRepository;

    private ExportQueryInterface $exportQuery;


    private CommandBusInterface $commandBus;

    public function __construct(
        ChannelRepositoryInterface $channelRepository,
        ExportQueryInterface $exportQuery,
        CommandBusInterface $commandBus
    ) {
        $this->channelRepository = $channelRepository;
        $this->exportQuery = $exportQuery;
        $this->commandBus = $commandBus;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(DeleteChannelCommand $deleteChannelCommand): void
    {
        $channel = $this->channelRepository->load($deleteChannelCommand->getId());
        Assert::isInstanceOf(
            $channel,
            AbstractChannel::class,
            sprintf('Can\'t find channel "%s"', $deleteChannelCommand->getId()->getValue())
        );

        $exportIds = $this->exportQuery->getExportIdsByChannelId($channel->getId());


        foreach ($exportIds as $exportId) {
            $exportDeletedCommand = new DeleteExportCommand($exportId);
            $this->commandBus->dispatch($exportDeletedCommand);
        }
        $this->channelRepository->delete($channel);
    }
}
