<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Application\Command;

use Ergonode\Channel\Domain\Command\StartExportChannelCommand;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Exporter\Domain\Command\Export\StartExportCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 */
class ExportChannelStartCommand extends Command
{
    protected static $defaultName = 'channel:export:start';

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
    public function __construct(ChannelRepositoryInterface $channelRepository, CommandBusInterface $commandBus)
    {
        parent::__construct();
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
        $command = new StartExportChannelCommand(
            ExportId::generate(),
            $channelId
        );
        $this->commandBus->dispatch($command);

        $output->writeln(
            sprintf('Start Processing Channel <comment>%s</comment>', $channel->getName())
        );
    }
}
