<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Persistence\Repository\Mapper;

use Ergonode\Channel\Domain\Entity\ExportLine;

class DbalExportLineMapper
{
    /**
     * @throws \JsonException
     */
    public function map(ExportLine $line): array
    {
        return [
            'export_id' => $line->getExportId()->getValue(),
            'object_id' => $line->getObjectId()->getValue(),
            'processed_at' => $line->getProcessedAt(),
            'message' => $line->getError(),
            'parameters' => json_encode($line->getParameters(), JSON_THROW_ON_ERROR),
        ];
    }
}
