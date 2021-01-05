<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Domain\Command\Export;

use Ergonode\Exporter\Domain\Command\RemoveExportArtifactsCommandInterface;
use JMS\Serializer\Annotation as JMS;

class RemoveExporterFileArtifactsCommand implements RemoveExportArtifactsCommandInterface
{
    /**
     * @JMS\Type("string")
     */
    private string $exportId;

    public function __construct(string $exportId)
    {
        $this->exportId = $exportId;
    }

    public function getExportId(): string
    {
        return $this->exportId;
    }
}
