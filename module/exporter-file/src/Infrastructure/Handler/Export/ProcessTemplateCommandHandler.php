<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Handler\Export;

use Webmozart\Assert\Assert;
use Ergonode\Core\Infrastructure\Service\TempFileStorage;
use Ergonode\ExporterFile\Infrastructure\Provider\WriterProvider;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;
use Ergonode\Channel\Domain\Repository\ChannelRepositoryInterface;
use Ergonode\ExporterFile\Domain\Command\Export\ProcessTemplateCommand;
use Ergonode\ExporterFile\Infrastructure\Processor\TemplateProcessor;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Ergonode\Designer\Domain\Entity\Template;
use Psr\Log\LoggerInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\ExporterFile\Infrastructure\Processor\TemplateElementProcessor;

class ProcessTemplateCommandHandler
{
    private TemplateRepositoryInterface $templateRepository;

    private ExportRepositoryInterface $exportRepository;

    private ChannelRepositoryInterface $channelRepository;

    private LoggerInterface $logger;

    private TemplateProcessor $processor;

    private TemplateElementProcessor $elementProcessor;

    private TempFileStorage $storage;

    private WriterProvider $provider;

    public function __construct(
        TemplateRepositoryInterface $templateRepository,
        ExportRepositoryInterface $exportRepository,
        ChannelRepositoryInterface $channelRepository,
        LoggerInterface $logger,
        TemplateProcessor $processor,
        TemplateElementProcessor $elementProcessor,
        TempFileStorage $storage,
        WriterProvider $provider
    ) {
        $this->templateRepository = $templateRepository;
        $this->exportRepository = $exportRepository;
        $this->channelRepository = $channelRepository;
        $this->logger = $logger;
        $this->processor = $processor;
        $this->elementProcessor = $elementProcessor;
        $this->storage = $storage;
        $this->provider = $provider;
    }

    public function __invoke(ProcessTemplateCommand $command): void
    {
        $exportId = $command->getExportId();
        $templateId = $command->getTemplateId();
        $export = $this->exportRepository->load($exportId);
        if ($export instanceof Export) {
            try {
                $export = $this->exportRepository->load($exportId);
                Assert::isInstanceOf($export, Export::class);
                /** @var FileExportChannel $channel */
                $channel = $this->channelRepository->load($export->getChannelId());
                Assert::isInstanceOf($channel, FileExportChannel::class);
                $template = $this->templateRepository->load($templateId);
                Assert::isInstanceOf($template, Template::class);

                $this->processTemplate($exportId, $channel, $template);

                $this->processTemplateElement($exportId, $channel, $template);
            } catch (\Exception $exception) {
                $this->logger->error($exception);
                $this->exportRepository->addError(
                    $exportId,
                    'Can\'t export template {id}',
                    ['{id}' => $templateId->getValue()]
                );
            }
            $this->exportRepository->processLine($command->getLineId());
        }
    }

    private function processTemplate(ExportId $exportId, FileExportChannel $channel, Template $template): void
    {
        $filename = sprintf('%s/templates.%s', $exportId->getValue(), $channel->getFormat());
        $data = $this->processor->process($channel, $template);
        $writer = $this->provider->provide($channel->getFormat());
        $lines = $writer->add($data);

        $this->storage->open($filename);
        $this->storage->append($lines);
        $this->storage->close();
    }

    private function processTemplateElement(ExportId $exportId, FileExportChannel $channel, Template $template): void
    {
        if (!$template->getElements()->isEmpty()) {
            $lines = [];
            $filename = sprintf('%s/templates_elements.%s', $exportId->getValue(), $channel->getFormat());
            $data = $this->elementProcessor->process($channel, $template);
            $writer = $this->provider->provide($channel->getFormat());
            foreach ($writer->add($data) as $line) {
                $lines[] = $line;
            }
            $this->storage->open($filename);
            $this->storage->append($lines);
            $this->storage->close();
        }
    }
}
