<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Exporter\Infrastructure\Factory\Command;

use Ergonode\Exporter\Domain\Command\RemoveExportArtifactsCommandInterface;

interface RemoveExportArtifactsCommandFactoryInterface
{
    public function support(string $type): bool;

    public function create(string $exportId): RemoveExportArtifactsCommandInterface;
}
