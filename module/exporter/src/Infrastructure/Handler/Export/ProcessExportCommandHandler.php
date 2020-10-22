<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\Handler\Export;

use Ergonode\Exporter\Domain\Repository\ExportRepositoryInterface;
use Ergonode\Exporter\Infrastructure\Provider\ExportProcessorProvider;
use Ergonode\Exporter\Domain\Command\Export\ProcessExportCommand;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Exporter\Domain\Repository\ExportLineRepositoryInterface;
use Doctrine\DBAL\DBALException;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;

class ProcessExportCommandHandler
{
    /**
     * @var ExportRepositoryInterface
     */
    private ExportRepositoryInterface $exportRepository;

    /**
     * @var ExportLineRepositoryInterface
     */
    private ExportLineRepositoryInterface $lineRepository;

    /**
     * @var ChannelRepositoryInterface
     */
    private ChannelRepositoryInterface $channelRepository;

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var ExportProcessorProvider
     */
    private ExportProcessorProvider $provider;

    /**
     * @param ExportRepositoryInterface     $exportRepository
     * @param ExportLineRepositoryInterface $lineRepository
     * @param ChannelRepositoryInterface    $channelRepository
     * @param ProductRepositoryInterface    $productRepository
     * @param ExportProcessorProvider       $provider
     */
    public function __construct(
        ExportRepositoryInterface $exportRepository,
        ExportLineRepositoryInterface $lineRepository,
        ChannelRepositoryInterface $channelRepository,
        ProductRepositoryInterface $productRepository,
        ExportProcessorProvider $provider
    ) {
        $this->exportRepository = $exportRepository;
        $this->lineRepository = $lineRepository;
        $this->channelRepository = $channelRepository;
        $this->productRepository = $productRepository;
        $this->provider = $provider;
    }

    /**
     * @param ProcessExportCommand $command
     *
     * @throws DBALException
     */
    public function __invoke(ProcessExportCommand $command)
    {
//        $export = $this->exportRepository->load($command->getExportId());
//        Assert::notNull($export);
//        $channel = $this->channelRepository->load($export->getChannelId());
//        Assert::notNull($channel);
//        $product = $this->productRepository->load($command->getProductId());
//        Assert::notNull($product);
//
//        $line = new ExportLine($export->getId(), $product->getId());
//        try {
//            $processor = $this->provider->provide($channel->getType());
//            $processor->process($command->getExportId(), $channel, $product);
//            $line->process();
//        } catch (ExportException $exception) {
//            $message = $exception->getMessage();
//            if ($exception->getPrevious()) {
//                sprintf('%s - (%s)', $message, $exception->getPrevious()->getMessage());
//            }
//            $line->addError($message);
//        }
//
//        $this->lineRepository->save($line);
    }
}
