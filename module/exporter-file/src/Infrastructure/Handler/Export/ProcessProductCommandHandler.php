<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Handler\Export;

use Webmozart\Assert\Assert;
use Ergonode\Exporter\Infrastructure\Exception\ExportException;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\ExporterFile\Infrastructure\Processor\ProductProcessor;
use Ergonode\ExporterFile\Domain\Command\Export\ProcessProductCommand;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\ExporterFile\Infrastructure\Storage\FileStorage;
use Ergonode\ExporterFile\Infrastructure\Provider\WriterProvider;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\Exporter\Domain\Repository\ExportRepositoryInterface;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;

/**
 */
class ProcessProductCommandHandler
{
    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var ExportRepositoryInterface
     */
    private ExportRepositoryInterface $exportRepository;

    /**
     * @var ChannelRepositoryInterface
     */
    private ChannelRepositoryInterface $channelRepository;

    /**
     * @var ProductProcessor
     */
    private ProductProcessor $processor;

    /**
     * @var FileStorage
     */
    private FileStorage $storage;

    /**
     * @var WriterProvider
     */
    private WriterProvider $provider;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param ExportRepositoryInterface  $exportRepository
     * @param ChannelRepositoryInterface $channelRepository
     * @param ProductProcessor           $processor
     * @param FileStorage                $storage
     * @param WriterProvider             $provider
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        ExportRepositoryInterface $exportRepository,
        ChannelRepositoryInterface $channelRepository,
        ProductProcessor $processor,
        FileStorage $storage,
        WriterProvider $provider
    ) {
        $this->productRepository = $productRepository;
        $this->exportRepository = $exportRepository;
        $this->channelRepository = $channelRepository;
        $this->processor = $processor;
        $this->storage = $storage;
        $this->provider = $provider;
    }

    /**
     * @param ProcessProductCommand $command
     *
     * @throws ExportException
     */
    public function __invoke(ProcessProductCommand $command)
    {
        $export = $this->exportRepository->load($command->getExportId());
        Assert::isInstanceOf($export, Export::class);
        $channel = $this->channelRepository->load($export->getChannelId());
        Assert::isInstanceOf($channel, FileExportChannel::class);
        $product = $this->productRepository->load($command->getProductId());
        Assert::isInstanceOf($product, AbstractProduct::class);

        $filename = sprintf('%s/products.csv', $command->getExportId()->getValue());
        $data = $this->processor->process($channel, $product);
        $writer = $this->provider->provide('csv');
        $lines = $writer->add($data);

        $this->storage->open($filename);
        $this->storage->append($lines);
        $this->storage->close();
    }
}
