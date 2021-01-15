<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Handler\Export;

use Webmozart\Assert\Assert;
use Ergonode\Channel\Infrastructure\Exception\ExportException;
use Ergonode\Core\Infrastructure\Service\TempFileStorage;
use Ergonode\ExporterFile\Infrastructure\Provider\WriterProvider;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\Attribute\Domain\Repository\OptionRepositoryInterface;
use Ergonode\Attribute\Domain\Entity\AbstractOption;
use Ergonode\ExporterFile\Domain\Command\Export\ProcessOptionCommand;
use Ergonode\ExporterFile\Infrastructure\Processor\OptionProcessor;

class ProcessOptionCommandHandler
{
    private OptionRepositoryInterface $optionRepository;

    private ExportRepositoryInterface $exportRepository;

    private ChannelRepositoryInterface $channelRepository;

    private OptionProcessor $processor;

    private TempFileStorage $storage;

    private WriterProvider $provider;

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
     * @throws ExportException
     */
    public function __invoke(ProcessOptionCommand $command): void
    {
        $export = $this->exportRepository->load($command->getExportId());
        Assert::isInstanceOf($export, Export::class);
        $channel = $this->channelRepository->load($export->getChannelId());
        /** @var FileExportChannel $channel */
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
