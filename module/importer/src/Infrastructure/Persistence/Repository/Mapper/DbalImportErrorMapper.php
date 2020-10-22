<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Persistence\Repository\Mapper;

use Ergonode\Importer\Domain\Entity\ImportError;

class DbalImportErrorMapper
{
    /**
     * @param ImportError $importLine
     *
     * @return array
     */
    public function map(ImportError $importLine): array
    {
        $importId = $importLine->getImportId()->getValue();
        $createdAt = $importLine->getCreatedAt();
        $message = $importLine->getMessage();

        return [
            'import_id' => $importId,
            'created_at' => $createdAt,
            'message' => $message,
        ];
    }
}
