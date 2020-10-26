<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Handler\Export;

use Webmozart\Assert\Assert;
use Ergonode\Exporter\Infrastructure\Exception\ExportException;
use Ergonode\ExporterFile\Infrastructure\Processor\AttributeProcessor;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\ExporterFile\Domain\Command\Export\ProcessAttributeCommand;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Infrastructure\Service\TempFileStorage;
use Ergonode\ExporterFile\Infrastructure\Provider\WriterProvider;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\Exporter\Domain\Repository\ExportRepositoryInterface;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;

class ProcessAttributeCommandHandler
{
    private AttributeRepositoryInterface $attributeRepository;

    private ExportRepositoryInterface $exportRepository;

    private ChannelRepositoryInterface $channelRepository;

    private AttributeProcessor $processor;

    private TempFileStorage $storage;

    private WriterProvider $provider;

    public function __construct(
        AttributeRepositoryInterface $attributeRepository,
        ExportRepositoryInterface $exportRepository,
        ChannelRepositoryInterface $channelRepository,
        AttributeProcessor $processor,
        TempFileStorage $storage,
        WriterProvider $provider
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->exportRepository = $exportRepository;
        $this->channelRepository = $channelRepository;
        $this->processor = $processor;
        $this->storage = $storage;
        $this->provider = $provider;
    }

    /**
     * @throws ExportException
     */
    public function __invoke(ProcessAttributeCommand $command): void
    {
        /** @var FileExportChannel $channel */
        $export = $this->exportRepository->load($command->getExportId());
        Assert::isInstanceOf($export, Export::class);
        $channel = $this->channelRepository->load($export->getChannelId());
        Assert::isInstanceOf($channel, FileExportChannel::class);
        $attribute = $this->attributeRepository->load($command->getAttributeId());
        Assert::isInstanceOf($attribute, AbstractAttribute::class);

        $filename = sprintf('%s/attributes.%s', $command->getExportId()->getValue(), $channel->getFormat());
        $data = $this->processor->process($channel, $attribute);
        $writer = $this->provider->provide($channel->getFormat());
        $lines = $writer->add($data);

        $this->storage->open($filename);
        $this->storage->append($lines);
        $this->storage->close();
    }
}
