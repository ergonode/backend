<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Persistence\Dbal\Repository\Mapper;

use Ergonode\Importer\Domain\Entity\ImportLine;

/**
 */
class ImportLineMapper
{
    /**
     * @param ImportLine $importLine
     *
     * @return array
     */
    public function map(ImportLine $importLine): array
    {
        $importId = $importLine->getImportId()->getValue();
        $processedAt = $importLine->getProcessedAt();
        $line = $importLine->getLine();
        $step = $importLine->getStep();
        $error = $importLine->getError();

        return [
            'import_id' => $importId,
            'line' => $line,
            'step' => $step,
            'processed_at' => $processedAt ? $processedAt->format('Y-m-d H:i:s') : null,
            'message' => $error,
        ];
    }
}
