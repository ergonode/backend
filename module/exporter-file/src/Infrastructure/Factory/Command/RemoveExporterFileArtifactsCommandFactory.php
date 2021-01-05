<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Factory\Command;

use Ergonode\Exporter\Domain\Command\RemoveExportArtifactsCommandInterface;
use Ergonode\Exporter\Infrastructure\Factory\Command\RemoveExportArtifactsCommandFactoryInterface;
use Ergonode\ExporterFile\Domain\Command\Export\RemoveExporterFileArtifactsCommand;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;

class RemoveExporterFileArtifactsCommandFactory implements RemoveExportArtifactsCommandFactoryInterface
{
    public function support(string $type): bool
    {
        return $type === FileExportChannel::TYPE;
    }

    public function create(string $exportId): RemoveExportArtifactsCommandInterface
    {
        return new RemoveExporterFileArtifactsCommand($exportId);
    }
}
