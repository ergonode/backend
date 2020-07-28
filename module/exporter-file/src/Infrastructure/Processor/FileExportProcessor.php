<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Processor;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\ExporterFile\Infrastructure\Processor\Process\StartFileExportProcess;
use Ergonode\ExporterFile\Infrastructure\Processor\Process\ProcessFileExportProcess;
use Ergonode\ExporterFile\Infrastructure\Processor\Process\EndFileExportProcess;
use Ergonode\Exporter\Infrastructure\Processor\ExportProcessorInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\Exporter\Infrastructure\Exception\ExportException;
use Ergonode\Channel\Domain\Entity\AbstractChannel;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;

/**
 */
class FileExportProcessor implements ExportProcessorInterface
{
    /**
     * @var StartFileExportProcess
     */
    private StartFileExportProcess $startProcessor;

    /**
     * @var ProcessFileExportProcess
     */
    private ProcessFileExportProcess $processProcessor;

    /**
     * @var EndFileExportProcess
     */
    private EndFileExportProcess $endProcessor;

    /**
     * @param StartFileExportProcess   $startProcessor
     * @param ProcessFileExportProcess $processProcessor
     * @param EndFileExportProcess     $endProcessor
     */
    public function __construct(
        StartFileExportProcess $startProcessor,
        ProcessFileExportProcess $processProcessor,
        EndFileExportProcess $endProcessor
    ) {
        $this->startProcessor = $startProcessor;
        $this->processProcessor = $processProcessor;
        $this->endProcessor = $endProcessor;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool
    {
        return FileExportChannel::TYPE === $type;
    }

    /**
     * @param ExportId        $id
     * @param AbstractChannel $channel
     */
    public function start(ExportId $id, AbstractChannel $channel): void
    {
        $this->startProcessor->process($id, $channel);
    }

    /**
     * @param ExportId        $id
     * @param AbstractChannel $channel
     * @param AbstractProduct $product
     *
     * @throws ExportException
     */
    public function process(ExportId $id, AbstractChannel $channel, AbstractProduct $product): void
    {
        $this->processProcessor->process($id, $channel, $product);
    }

    /**
     * @param ExportId        $id
     * @param AbstractChannel $channel
     */
    public function end(ExportId $id, AbstractChannel $channel): void
    {
        $this->endProcessor->process($id, $channel);
    }
}
