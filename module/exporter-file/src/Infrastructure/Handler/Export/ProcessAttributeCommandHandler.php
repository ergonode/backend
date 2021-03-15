<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Handler\Export;

use Webmozart\Assert\Assert;
use Ergonode\Channel\Infrastructure\Exception\ExportException;
use Ergonode\ExporterFile\Infrastructure\Processor\AttributeProcessor;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\ExporterFile\Domain\Command\Export\ProcessAttributeCommand;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Infrastructure\Service\TempFileStorage;
use Ergonode\ExporterFile\Infrastructure\Provider\WriterProvider;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Psr\Log\LoggerInterface;

class ProcessAttributeCommandHandler
{
    private AttributeRepositoryInterface $attributeRepository;

    private ExportRepositoryInterface $exportRepository;

    private ChannelRepositoryInterface $channelRepository;

    private LoggerInterface $logger;

    private AttributeProcessor $processor;

    private TempFileStorage $storage;

    private WriterProvider $provider;

    public function __construct(
        AttributeRepositoryInterface $attributeRepository,
        ExportRepositoryInterface $exportRepository,
        ChannelRepositoryInterface $channelRepository,
        LoggerInterface $logger,
        AttributeProcessor $processor,
        TempFileStorage $storage,
        WriterProvider $provider
    ) {
        $this->attributeRepository = $attributeRepository;
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
    public function __invoke(ProcessAttributeCommand $command): void
    {
        $exportId = $command->getExportId();
        $attributeId = $command->getAttributeId();
        $export = $this->exportRepository->load($exportId);
        if ($export instanceof Export) {
            try {
                $export = $this->exportRepository->load($exportId);
                Assert::isInstanceOf($export, Export::class);
                $channel = $this->channelRepository->load($export->getChannelId());
                /** @var FileExportChannel $channel */
                Assert::isInstanceOf($channel, FileExportChannel::class);
                $attribute = $this->attributeRepository->load($attributeId);
                Assert::isInstanceOf($attribute, AbstractAttribute::class);

                $filename = sprintf('%s/attributes.%s', $exportId->getValue(), $channel->getFormat());
                $data = $this->processor->process($channel, $attribute);
                $writer = $this->provider->provide($channel->getFormat());
                $lines = $writer->add($data);

                $this->storage->open($filename);
                $this->storage->append($lines);
                $this->storage->close();
            } catch (\Exception $exception) {
                $this->logger->error($exception);
                $this->exportRepository->addError(
                    $exportId,
                    'Can\'t export attribute {id}',
                    ['{id}' => $attributeId->getValue()]
                );
            }
            $this->exportRepository->processLine($command->getLineId());
        }
    }
}
