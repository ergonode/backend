<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Handler\Export;

use Webmozart\Assert\Assert;
use Ergonode\Channel\Infrastructure\Exception\ExportException;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\ExporterFile\Infrastructure\Processor\ProductProcessor;
use Ergonode\ExporterFile\Domain\Command\Export\ProcessProductCommand;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Core\Infrastructure\Service\TempFileStorage;
use Ergonode\ExporterFile\Infrastructure\Provider\WriterProvider;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Psr\Log\LoggerInterface;

class ProcessProductCommandHandler
{
    private ProductRepositoryInterface $productRepository;

    private ExportRepositoryInterface $exportRepository;

    private ChannelRepositoryInterface $channelRepository;

    private LoggerInterface $logger;

    private ProductProcessor $processor;

    private TempFileStorage $storage;

    private WriterProvider $provider;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        ExportRepositoryInterface $exportRepository,
        ChannelRepositoryInterface $channelRepository,
        LoggerInterface $logger,
        ProductProcessor $processor,
        TempFileStorage $storage,
        WriterProvider $provider
    ) {
        $this->productRepository = $productRepository;
        $this->exportRepository = $exportRepository;
        $this->channelRepository = $channelRepository;
        $this->logger = $logger;
        $this->processor = $processor;
        $this->storage = $storage;
        $this->provider = $provider;
    }

    /**
     * @throws ExportException
     */
    public function __invoke(ProcessProductCommand $command): void
    {
        $exportId = $command->getExportId();
        $productId = $command->getProductId();
        $export = $this->exportRepository->load($exportId);
        if ($export instanceof Export) {
            try {
                $export = $this->exportRepository->load($exportId);
                Assert::isInstanceOf($export, Export::class);
                /** @var FileExportChannel $channel */
                $channel = $this->channelRepository->load($export->getChannelId());
                Assert::isInstanceOf($channel, FileExportChannel::class);
                $product = $this->productRepository->load($productId);
                Assert::isInstanceOf($product, AbstractProduct::class);

                $filename = sprintf('%s/products.%s', $command->getExportId()->getValue(), $channel->getFormat());
                $data = $this->processor->process($channel, $product);
                $writer = $this->provider->provide($channel->getFormat());
                $lines = $writer->add($data);

                $this->storage->open($filename);
                $this->storage->append($lines);
                $this->storage->close();
            } catch (\Exception $exception) {
                $this->logger->error($exception);
                $this->exportRepository->addError(
                    $exportId,
                    'Can\'t export product {id}',
                    ['{id}' => $productId->getValue()]
                );
            }
            $this->exportRepository->processLine($command->getLineId());
        }
    }
}
