<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Exporter\Infrastructure\Handler\Export;

use Ergonode\Exporter\Domain\Command\Export\ProcessExportCommand;
use Doctrine\DBAL\DBALException;

class ProcessExportCommandHandler
{
//    private ExportRepositoryInterface $exportRepository;
//
//    private ExportLineRepositoryInterface $lineRepository;
//
//    private ChannelRepositoryInterface $channelRepository;
//
//    private ProductRepositoryInterface $productRepository;
//
//    private ExportProcessorProvider $provider;
//
//    public function __construct(
//        ExportRepositoryInterface $exportRepository,
//        ExportLineRepositoryInterface $lineRepository,
//        ChannelRepositoryInterface $channelRepository,
//        ProductRepositoryInterface $productRepository,
//        ExportProcessorProvider $provider
//    ) {
//        $this->exportRepository = $exportRepository;
//        $this->lineRepository = $lineRepository;
//        $this->channelRepository = $channelRepository;
//        $this->productRepository = $productRepository;
//        $this->provider = $provider;
//    }

    /**
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
