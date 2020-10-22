<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Handler\Export;

use Webmozart\Assert\Assert;
use Ergonode\Exporter\Infrastructure\Exception\ExportException;
use Ergonode\Core\Infrastructure\Service\TempFileStorage;
use Ergonode\ExporterFile\Infrastructure\Provider\WriterProvider;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\Exporter\Domain\Repository\ExportRepositoryInterface;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\Attribute\Domain\Repository\OptionRepositoryInterface;
use Ergonode\Attribute\Domain\Entity\AbstractOption;
use Ergonode\ExporterFile\Domain\Command\Export\ProcessOptionCommand;
use Ergonode\ExporterFile\Infrastructure\Processor\OptionProcessor;

class ProcessOptionCommandHandler
{
    /**
     * @var OptionRepositoryInterface
     */
    private OptionRepositoryInterface $optionRepository;

    /**
     * @var ExportRepositoryInterface
     */
    private ExportRepositoryInterface $exportRepository;

    /**
     * @var ChannelRepositoryInterface
     */
    private ChannelRepositoryInterface $channelRepository;

    /**
     * @var OptionProcessor
     */
    private OptionProcessor $processor;

    /**
     * @var TempFileStorage
     */
    private TempFileStorage $storage;

    /**
     * @var WriterProvider
     */
    private WriterProvider $provider;

    /**
     * @param OptionRepositoryInterface  $optionRepository
     * @param ExportRepositoryInterface  $exportRepository
     * @param ChannelRepositoryInterface $channelRepository
     * @param OptionProcessor            $processor
     * @param TempFileStorage            $storage
     * @param WriterProvider             $provider
     */
    public function __construct(
        OptionRepositoryInterface $optionRepository,
        ExportRepositoryInterface $exportRepository,
        ChannelRepositoryInterface $channelRepository,
        OptionProcessor $processor,
        TempFileStorage $storage,
        WriterProvider $provider
    ) {
        $this->optionRepository = $optionRepository;
        $this->exportRepository = $exportRepository;
        $this->channelRepository = $channelRepository;
        $this->processor = $processor;
        $this->storage = $storage;
        $this->provider = $provider;
    }

    /**
     * @param ProcessOptionCommand $command
     *
     * @throws ExportException
     */
    public function __invoke(ProcessOptionCommand $command)
    {
        /** @var FileExportChannel $channel */
        $export = $this->exportRepository->load($command->getExportId());
        Assert::isInstanceOf($export, Export::class);
        $channel = $this->channelRepository->load($export->getChannelId());
        Assert::isInstanceOf($channel, FileExportChannel::class);
        $option = $this->optionRepository->load($command->getOptionId());
        Assert::isInstanceOf($option, AbstractOption::class);

        $filename = sprintf('%s/options.%s', $command->getExportId()->getValue(), $channel->getFormat());
        $data = $this->processor->process($channel, $option);
        $writer = $this->provider->provide($channel->getFormat());
        $lines = $writer->add($data);

        $this->storage->open($filename);
        $this->storage->append($lines);
        $this->storage->close();
    }
}
