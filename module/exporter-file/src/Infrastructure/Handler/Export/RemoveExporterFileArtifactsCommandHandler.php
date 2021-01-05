<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Handler\Export;

use Ergonode\Exporter\Infrastructure\Service\ZipFileRemover;
use Ergonode\ExporterFile\Domain\Command\Export\RemoveExporterFileArtifactsCommand;

class RemoveExporterFileArtifactsCommandHandler
{
    private ZipFileRemover $zipFileRemover;

    public function __construct(ZipFileRemover $zipFileRemover)
    {
        $this->zipFileRemover = $zipFileRemover;
    }

    public function __invoke(RemoveExporterFileArtifactsCommand $command): void
    {
        $this->zipFileRemover->remove($command->getExportId());
    }
}
