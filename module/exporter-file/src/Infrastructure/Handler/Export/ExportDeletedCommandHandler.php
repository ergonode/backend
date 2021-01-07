<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Handler\Export;

use Ergonode\Exporter\Infrastructure\Service\ZipFileRemover;
use Ergonode\Exporter\Domain\Command\Export\ExportDeletedCommand;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;

class ExportDeletedCommandHandler
{
    private ZipFileRemover $zipFileRemover;

    public function __construct(ZipFileRemover $zipFileRemover)
    {
        $this->zipFileRemover = $zipFileRemover;
    }

    public function __invoke(ExportDeletedCommand $command): void
    {
        if (FileExportChannel::getType() === $command->getChannelType()) {
            $this->zipFileRemover->remove($command->getExportId()->getValue());
        }
    }
}
