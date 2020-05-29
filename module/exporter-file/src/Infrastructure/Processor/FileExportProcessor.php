<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Processor;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\ExporterFile\Domain\Entity\FileExportProfile;
use Ergonode\ExporterFile\Infrastructure\Processor\Process\StartFileExportProcess;
use Ergonode\ExporterFile\Infrastructure\Processor\Process\ProcessFileExportProcess;
use Ergonode\ExporterFile\Infrastructure\Processor\Process\EndFileExportProcess;
use Ergonode\Exporter\Infrastructure\Processor\ExportProcessorInterface;
use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\Exporter\Infrastructure\Exception\ExportException;

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
        return FileExportProfile::TYPE === $type;
    }

    /**
     * @param ExportId              $id
     * @param AbstractExportProfile $profile
     */
    public function start(ExportId $id, AbstractExportProfile $profile): void
    {
        $this->startProcessor->process($id, $profile);
    }

    /**
     * @param ExportId              $id
     * @param AbstractExportProfile $profile
     * @param AbstractProduct       $product
     *
     * @throws ExportException
     */
    public function process(ExportId $id, AbstractExportProfile $profile, AbstractProduct $product): void
    {
        $this->processProcessor->process($id, $profile, $product);
    }

    /**
     * @param ExportId              $id
     * @param AbstractExportProfile $profile
     */
    public function end(ExportId $id, AbstractExportProfile $profile): void
    {
        $this->endProcessor->process($id, $profile);
    }
}
