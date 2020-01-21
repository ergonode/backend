<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Application\Command;

use Ergonode\Channel\Domain\Command\ExportProductChannelCommand as ExportProductChannelDomainCommand;
use Ergonode\Channel\Domain\Entity\ChannelId;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Product\Domain\Entity\ProductId;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;

/**
 */
class ExportChannelCommand extends Command
{
    private const NAME = 'channel:export:all';

    /**
     * @var ProductQueryInterface
     */
    private ProductQueryInterface $query;

    /**
     * @var ChannelRepositoryInterface
     */
    private ChannelRepositoryInterface $channelRepository;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param ProductQueryInterface      $query
     * @param ChannelRepositoryInterface $channelRepository
     * @param CommandBusInterface        $commandBus
     */
    public function __construct(
        ProductQueryInterface $query,
        ChannelRepositoryInterface $channelRepository,
        CommandBusInterface $commandBus
    ) {
        parent::__construct(static::NAME);

        $this->query = $query;
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
        $stdout = $output instanceof StreamOutput ? new StreamOutput($output->getStream()) : $output;

        $channelId = new ChannelId($input->getArgument('channel'));
        $ids = $this->query->getAllIds();
        $progressBar = new ProgressBar($stdout, \count($ids));
        $progressBar->setFormat('debug');

        if (!$channel = $this->channelRepository->load($channelId)) {
            $output->writeln('Channel not exists');
        }

        $output->writeln(sprintf('Processing products witch channel <comment>%s</comment>', $channel->getName()));

        foreach ($ids as $id) {
            $progressBar->advance();
            $progressBar->setMessage($id);
            $productId = new ProductId($id);
            $command = new ExportProductChannelDomainCommand($channelId, $productId);
            $this->commandBus->dispatch($command);
        }

        $progressBar->finish();
    }
}
