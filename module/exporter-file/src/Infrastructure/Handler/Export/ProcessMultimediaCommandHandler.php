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
use Ergonode\ExporterFile\Domain\Command\Export\ProcessMultimediaCommand;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Ergonode\Multimedia\Domain\Entity\AbstractMultimedia;
use Ergonode\ExporterFile\Infrastructure\Processor\MultimediaProcessor;
use Psr\Log\LoggerInterface;

class ProcessMultimediaCommandHandler
{
    private MultimediaRepositoryInterface $multimediaRepository;

    private ExportRepositoryInterface $exportRepository;

    private ChannelRepositoryInterface $channelRepository;

    private LoggerInterface $logger;

    private MultimediaProcessor $processor;

    private TempFileStorage $storage;

    private WriterProvider $provider;

    public function __construct(
        MultimediaRepositoryInterface $multimediaRepository,
        ExportRepositoryInterface $exportRepository,
        ChannelRepositoryInterface $channelRepository,
        LoggerInterface $logger,
        MultimediaProcessor $processor,
        TempFileStorage $storage,
        WriterProvider $provider
    ) {
        $this->multimediaRepository = $multimediaRepository;
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
    public function __invoke(ProcessMultimediaCommand $command): void
    {
        $exportId = $command->getExportId();
        $multimediaId = $command->getMultimediaId();
        $export = $this->exportRepository->load($exportId);
        if ($export instanceof Export) {
            try {
                $channel = $this->channelRepository->load($export->getChannelId());
                /** @var FileExportChannel $channel */
                Assert::isInstanceOf($channel, FileExportChannel::class);
                $multimedia = $this->multimediaRepository->load($multimediaId);
                Assert::isInstanceOf($multimedia, AbstractMultimedia::class);

                $filename = sprintf('%s/multimedia.%s', $exportId->getValue(), $channel->getFormat());
                $data = $this->processor->process($channel, $multimedia);
                $writer = $this->provider->provide($channel->getFormat());
                $lines = $writer->add($data);

                $this->storage->open($filename);
                $this->storage->append($lines);
                $this->storage->close();
            } catch (\Exception $exception) {
                $this->logger->error($exception);
                $this->exportRepository->addError(
                    $exportId,
                    'Can\'t export multimedia {id}',
                    ['{id}' => $multimediaId->getValue()]
                );
            }
            $this->exportRepository->processLine($command->getLineId());
        }
    }
}
