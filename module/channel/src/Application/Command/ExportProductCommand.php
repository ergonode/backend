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
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 */
class ExportProductCommand extends Command
{
    private const NAME = 'exporter:channel:export-product';

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var ChannelRepositoryInterface
     */
    private $channelRepository;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @param MessageBusInterface        $messageBus
     * @param ChannelRepositoryInterface $channelRepository
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(MessageBusInterface $messageBus, ChannelRepositoryInterface $channelRepository, ProductRepositoryInterface $productRepository)
    {
        parent::__construct(static::NAME);

        $this->messageBus = $messageBus;
        $this->channelRepository = $channelRepository;
        $this->productRepository = $productRepository;
    }


    /**
     * Command configuration
     */
    public function configure(): void
    {
        $this->addArgument('channel', InputArgument::REQUIRED, 'Channel ID.');
        $this->addArgument('product', InputArgument::REQUIRED, 'Product ID.');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $channelId = $input->getArgument('channel');
        $productId = $input->getArgument('product');

        $channelId = new ChannelId($channelId);
        $productId = new ProductId($productId);

        if (!$channel = $this->channelRepository->load($channelId)) {
            $output->writeln('Channel not exists');
        }

        if (!$product = $this->productRepository->load($productId)) {
            $output->writeln('Product not exists');
        }

        $output->writeln(sprintf('Processing <comment>%s</comment> witch channel <comment>%s</comment>', $product->getSku()->getValue(), $channel->getName()));

        $command = new \Ergonode\Channel\Domain\Command\ExportProductCommand($channelId, $productId);

        $this->messageBus->dispatch($command);

        $output->writeln('<info>done.</info>');
    }
}
