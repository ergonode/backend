<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Persistence\Dbal\Repository\Mapper;

use Ergonode\Importer\Domain\Entity\ImportLine;

/**
 * Class ImportLineMapper
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
            'id' => $importLine->getId()->getValue(),
            'import_id' => $importLine->getImportId()->getValue(),
            'line' => $importLine->getContent(),
        ];
    }
}
