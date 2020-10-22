<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Handler\Export;

use Ergonode\ExporterFile\Domain\Command\Export\ProcessCategoryCommand;
use Webmozart\Assert\Assert;
use Ergonode\ExporterFile\Infrastructure\Processor\CategoryProcessor;
use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\Exporter\Infrastructure\Exception\ExportException;
use Ergonode\Core\Infrastructure\Service\TempFileStorage;
use Ergonode\ExporterFile\Infrastructure\Provider\WriterProvider;
use Ergonode\Category\Domain\Entity\AbstractCategory;
use Ergonode\Exporter\Domain\Repository\ExportRepositoryInterface;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;

class ProcessCategoryCommandHandler
{
    /**
     * @var CategoryRepositoryInterface
     */
    private CategoryRepositoryInterface $categoryRepository;

    /**
     * @var ExportRepositoryInterface
     */
    private ExportRepositoryInterface $exportRepository;

    /**
     * @var ChannelRepositoryInterface
     */
    private ChannelRepositoryInterface $channelRepository;

    /**
     * @var CategoryProcessor
     */
    private CategoryProcessor $processor;

    /**
     * @var TempFileStorage
     */
    private TempFileStorage $storage;

    /**
     * @var WriterProvider
     */
    private WriterProvider $provider;

    /**
     * @param CategoryRepositoryInterface $categoryRepository
     * @param ExportRepositoryInterface   $exportRepository
     * @param ChannelRepositoryInterface  $channelRepository
     * @param CategoryProcessor           $processor
     * @param TempFileStorage             $storage
     * @param WriterProvider              $provider
     */
    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        ExportRepositoryInterface $exportRepository,
        ChannelRepositoryInterface $channelRepository,
        CategoryProcessor $processor,
        TempFileStorage $storage,
        WriterProvider $provider
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->exportRepository = $exportRepository;
        $this->channelRepository = $channelRepository;
        $this->processor = $processor;
        $this->storage = $storage;
        $this->provider = $provider;
    }

    /**
     * @param ProcessCategoryCommand $command
     *
     * @throws ExportException
     */
    public function __invoke(ProcessCategoryCommand $command)
    {
        /** @var FileExportChannel $channel */
        $export = $this->exportRepository->load($command->getExportId());
        Assert::isInstanceOf($export, Export::class);
        $channel = $this->channelRepository->load($export->getChannelId());
        Assert::isInstanceOf($channel, FileExportChannel::class);
        $category = $this->categoryRepository->load($command->getCategoryId());
        Assert::isInstanceOf($category, AbstractCategory::class);

        $filename = sprintf('%s/categories.%s', $command->getExportId()->getValue(), $channel->getFormat());
        $data = $this->processor->process($channel, $category);
        $writer = $this->provider->provide($channel->getFormat());
        $lines = $writer->add($data);

        $this->storage->open($filename);
        $this->storage->append($lines);
        $this->storage->close();
    }
}
