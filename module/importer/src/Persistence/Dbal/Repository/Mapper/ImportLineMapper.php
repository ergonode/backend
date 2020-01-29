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
        return [
            'import_id' => $importLine->getImportId()->getValue(),
            'line' => $importLine->getLine(),
            'content' => $importLine->getContent(),
            'message' => $importLine->getError(),
        ];
    }
}
