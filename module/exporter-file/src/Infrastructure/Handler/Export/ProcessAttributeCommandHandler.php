<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

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

/**
 */
class ProcessAttributeCommandHandler
{
    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $attributeRepository;

    /**
     * @var ExportRepositoryInterface
     */
    private ExportRepositoryInterface $exportRepository;

    /**
     * @var ChannelRepositoryInterface
     */
    private ChannelRepositoryInterface $channelRepository;

    /**
     * @var AttributeProcessor;
     */
    private AttributeProcessor $processor;

    /**
     * @var TempFileStorage
     */
    private TempFileStorage $storage;

    /**
     * @var WriterProvider
     */
    private WriterProvider $provider;

    /**
     * @param AttributeRepositoryInterface $attributeRepository
     * @param ExportRepositoryInterface    $exportRepository
     * @param ChannelRepositoryInterface   $channelRepository
     * @param AttributeProcessor           $processor
     * @param TempFileStorage              $storage
     * @param WriterProvider               $provider
     */
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
     * @param ProcessAttributeCommand $command
     *
     * @throws ExportException
     */
    public function __invoke(ProcessAttributeCommand $command)
    {
        $export = $this->exportRepository->load($command->getExportId());
        Assert::isInstanceOf($export, Export::class);
        $channel = $this->channelRepository->load($export->getChannelId());
        Assert::isInstanceOf($channel, FileExportChannel::class);
        $attribute = $this->attributeRepository->load($command->getAttributeId());
        Assert::isInstanceOf($attribute, AbstractAttribute::class);

        $filename = sprintf('%s/attributes.csv', $command->getExportId()->getValue());
        $data = $this->processor->process($channel, $attribute);
        $writer = $this->provider->provide('csv');
        $lines = $writer->add($data);

        $this->storage->open($filename);
        $this->storage->append($lines);
        $this->storage->close();
    }
}
