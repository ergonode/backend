<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Application\Command;

use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\Channel\Domain\Command\ExportChannelCommand as ExportChannelDomainCommand;

/**
 */
class ExportChannelCommand extends Command
{
    private const NAME = 'channel:export';

    /**
     * @var ChannelRepositoryInterface
     */
    private ChannelRepositoryInterface $channelRepository;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param ChannelRepositoryInterface $channelRepository
     * @param CommandBusInterface        $commandBus
     */
    public function __construct(
        ChannelRepositoryInterface $channelRepository,
        CommandBusInterface $commandBus
    ) {
        parent::__construct(static::NAME);

        $this->channelRepository = $channelRepository;
        $this->commandBus = $commandBus;
    }

    /**
     */
    public function configure(): void
    {
        $this->addArgument('channel', InputArgument::REQUIRED, 'channel ID.');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $channelId = new ChannelId($input->getArgument('channel'));

        if (!$channel = $this->channelRepository->load($channelId)) {
            $output->writeln('Channel not exists');
        }

        $output->writeln(sprintf('Processing products witch channel <comment>%s</comment>', $channel->getName()));

        $command = new ExportChannelDomainCommand(ExportId::generate(), $channelId);

        $this->commandBus->dispatch($command);
    }
}
