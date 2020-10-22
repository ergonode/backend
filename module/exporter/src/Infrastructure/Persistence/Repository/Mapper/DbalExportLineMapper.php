<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\Persistence\Repository\Mapper;

use Ergonode\Exporter\Domain\Entity\ExportLine;

class DbalExportLineMapper
{
    /**
     * @param ExportLine $line
     *
     * @return array
     */
    public function map(ExportLine $line): array
    {
        return [
            'export_id' => $line->getExportId()->getValue(),
            'object_id' => $line->getObjectId()->getValue(),
            'processed_at' => $line->getProcessedAt(),
            'message' => $line->getError(),
        ];
    }
}
