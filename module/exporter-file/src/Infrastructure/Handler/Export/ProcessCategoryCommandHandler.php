<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Handler\Export;

use Ergonode\ExporterFile\Domain\Command\Export\ProcessCategoryCommand;
use Webmozart\Assert\Assert;
use Ergonode\ExporterFile\Infrastructure\Processor\CategoryProcessor;
use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\Channel\Infrastructure\Exception\ExportException;
use Ergonode\Core\Infrastructure\Service\TempFileStorage;
use Ergonode\ExporterFile\Infrastructure\Provider\WriterProvider;
use Ergonode\Category\Domain\Entity\AbstractCategory;
use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Psr\Log\LoggerInterface;

class ProcessCategoryCommandHandler
{
    private CategoryRepositoryInterface $categoryRepository;

    private ExportRepositoryInterface $exportRepository;

    private ChannelRepositoryInterface $channelRepository;

    private LoggerInterface $logger;

    private CategoryProcessor $processor;

    private TempFileStorage $storage;

    private WriterProvider $provider;

    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        ExportRepositoryInterface $exportRepository,
        ChannelRepositoryInterface $channelRepository,
        LoggerInterface $logger,
        CategoryProcessor $processor,
        TempFileStorage $storage,
        WriterProvider $provider
    ) {
        $this->categoryRepository = $categoryRepository;
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
    public function __invoke(ProcessCategoryCommand $command): void
    {
        $exportId = $command->getExportId();
        $categoryId = $command->getCategoryId();
        $export = $this->exportRepository->load($exportId);
        if ($export instanceof Export) {
            try {
                $export = $this->exportRepository->load($exportId);
                Assert::isInstanceOf($export, Export::class);
                $channel = $this->channelRepository->load($export->getChannelId());
                /** @var FileExportChannel $channel */
                Assert::isInstanceOf($channel, FileExportChannel::class);
                $category = $this->categoryRepository->load($categoryId);
                Assert::isInstanceOf($category, AbstractCategory::class);

                $filename = sprintf('%s/categories.%s', $exportId->getValue(), $channel->getFormat());
                $data = $this->processor->process($channel, $category);
                $writer = $this->provider->provide($channel->getFormat());
                $lines = $writer->add($data);

                $this->storage->open($filename);
                $this->storage->append($lines);
                $this->storage->close();
            } catch (\Exception $exception) {
                $this->logger->error($exception);
                $this->exportRepository->addError(
                    $exportId,
                    'Can\'t export category {id}',
                    ['{id}' => $categoryId->getValue()]
                );
            }
            $this->exportRepository->processLine($command->getLineId());
        }
    }
}
