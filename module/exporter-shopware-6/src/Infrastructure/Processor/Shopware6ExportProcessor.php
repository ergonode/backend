<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor;

use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Ergonode\Exporter\Infrastructure\Processor\ExportProcessorInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\ExporterShopware6\Infrastructure\Processor\Process\EndShopware6ExportProcess;
use Ergonode\ExporterShopware6\Infrastructure\Processor\Process\ProcessShopware6ExportProcess;
use Ergonode\ExporterShopware6\Infrastructure\Processor\Process\StartShopware6ExportProcess;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

/**
 */
class Shopware6ExportProcessor implements ExportProcessorInterface
{
    /**
     * @var StartShopware6ExportProcess
     */
    private StartShopware6ExportProcess $startProcess;

    /**
     * @var ProcessShopware6ExportProcess
     */
    private ProcessShopware6ExportProcess $processProcess;

    /**
     * @var EndShopware6ExportProcess
     */
    private EndShopware6ExportProcess $endProcess;

    /**
     * @param StartShopware6ExportProcess   $startProcess
     * @param ProcessShopware6ExportProcess $processProcess
     * @param EndShopware6ExportProcess     $endProcess
     */
    public function __construct(
        StartShopware6ExportProcess $startProcess,
        ProcessShopware6ExportProcess $processProcess,
        EndShopware6ExportProcess $endProcess
    ) {
        $this->startProcess = $startProcess;
        $this->processProcess = $processProcess;
        $this->endProcess = $endProcess;
    }

    /**
     * {@inheritDoc}
     */
    public function supported(string $type): bool
    {
        return Shopware6ExportApiProfile::TYPE === $type;
    }

    /**
     * {@inheritDoc}
     */
    public function start(ExportId $id, AbstractExportProfile $profile): void
    {
        $this->startProcess->process($id, $profile);
    }

    /**
     * {@inheritDoc}
     */
    public function process(ExportId $id, AbstractExportProfile $profile, AbstractProduct $product): void
    {
        $this->processProcess->process($id, $profile, $product);
    }

    /**
     * {@inheritDoc}
     */
    public function end(ExportId $id, AbstractExportProfile $profile): void
    {
        $this->endProcess->process($id, $profile);
    }
}
