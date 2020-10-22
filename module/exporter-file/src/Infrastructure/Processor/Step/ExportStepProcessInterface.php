<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Processor\Step;

use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;

interface ExportStepProcessInterface
{
    /**
     * @param ExportId          $exportId
     * @param FileExportChannel $channel
     */
    public function export(ExportId $exportId, FileExportChannel $channel): void;
}
