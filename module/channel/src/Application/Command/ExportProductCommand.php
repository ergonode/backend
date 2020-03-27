<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Application\Command;

use Ergonode\Channel\Domain\Command\ExportProductChannelCommand as ExportProductChannelDomainCommand;
use Ergonode\Exporter\Domain\Repository\ProductRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 */
class ExportProductCommand extends Command
{
    private const NAME = 'channel:export:product';

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @var ChannelRepositoryInterface
     */
    private ChannelRepositoryInterface $channelRepository;

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @param CommandBusInterface        $commandBus
     * @param ChannelRepositoryInterface $channelRepository
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        CommandBusInterface $commandBus,
        ChannelRepositoryInterface $channelRepository,
        ProductRepositoryInterface $productRepository
    ) {
        parent::__construct(static::NAME);

        $this->commandBus = $commandBus;
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
        $id = Uuid::fromString($productId->getValue());
        if (!$product = $this->productRepository->load($id)) {
            $output->writeln('Product not exists');
        }

        $output->writeln(
            sprintf(
                'Processing <comment>%s</comment> witch channel <comment>%s</comment>',
                $product->getSku(),
                $channel->getName()
            )
        );

        $command = new ExportProductChannelDomainCommand($channelId, $productId);

        $this->commandBus->dispatch($command);

        $output->writeln('<info>done.</info>');
    }
}
