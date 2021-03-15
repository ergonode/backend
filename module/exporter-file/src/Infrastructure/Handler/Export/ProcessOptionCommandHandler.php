<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
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
use Psr\Log\LoggerInterface;

class ProcessOptionCommandHandler
{
    private OptionRepositoryInterface $optionRepository;

    private ExportRepositoryInterface $exportRepository;

    private ChannelRepositoryInterface $channelRepository;

    private LoggerInterface $logger;

    private OptionProcessor $processor;

    private TempFileStorage $storage;

    private WriterProvider $provider;

    public function __construct(
        OptionRepositoryInterface $optionRepository,
        ExportRepositoryInterface $exportRepository,
        ChannelRepositoryInterface $channelRepository,
        LoggerInterface $logger,
        OptionProcessor $processor,
        TempFileStorage $storage,
        WriterProvider $provider
    ) {
        $this->optionRepository = $optionRepository;
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
    public function __invoke(ProcessOptionCommand $command): void
    {
        $exportId = $command->getExportId();
        $optionId = $command->getOptionId();
        $export = $this->exportRepository->load($exportId);
        if ($export instanceof Export) {
            try {
                $channel = $this->channelRepository->load($export->getChannelId());
                /** @var FileExportChannel $channel */
                Assert::isInstanceOf($channel, FileExportChannel::class);
                $option = $this->optionRepository->load($optionId);
                Assert::isInstanceOf($option, AbstractOption::class);

                $filename = sprintf('%s/options.%s', $exportId->getValue(), $channel->getFormat());
                $data = $this->processor->process($channel, $option);
                $writer = $this->provider->provide($channel->getFormat());
                $lines = $writer->add($data);

                $this->storage->open($filename);
                $this->storage->append($lines);
                $this->storage->close();
            } catch (\Exception $exception) {
                $this->logger->error($exception);
                $this->exportRepository->addError(
                    $exportId,
                    'Can\'t export option {id}',
                    ['{id}' => $optionId->getValue()]
                );
            }
            $this->exportRepository->processLine($command->getLineId());
        }
    }
}
