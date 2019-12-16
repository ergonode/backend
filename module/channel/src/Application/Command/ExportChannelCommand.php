<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Application\Command;

use Ergonode\Channel\Domain\Entity\ChannelId;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\Product\Domain\Entity\ProductId;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 */
class ExportChannelCommand extends Command
{
    private const NAME = 'exporter:channel:export-all';

    /**
     * @var ProductQueryInterface
     */
    private $query;

    /**
     * @var ChannelRepositoryInterface
     */
    private $channelRepository;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @param ProductQueryInterface      $query
     * @param ChannelRepositoryInterface $channelRepository
     * @param MessageBusInterface        $messageBus
     */
    public function __construct(ProductQueryInterface $query, ChannelRepositoryInterface $channelRepository, MessageBusInterface $messageBus)
    {
        parent::__construct(static::NAME);

        $this->query = $query;
        $this->channelRepository = $channelRepository;
        $this->messageBus = $messageBus;
    }


    /**
     * Command configuration
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
            $command = new \Ergonode\Channel\Domain\Command\ExportProductCommand($channelId, $productId);
            $this->messageBus->dispatch($command);
        }
        $progressBar->finish();
    }
}
