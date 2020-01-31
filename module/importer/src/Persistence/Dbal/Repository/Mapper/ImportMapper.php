<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Persistence\Dbal\Repository\Mapper;

use Ergonode\Importer\Domain\Entity\Import;

/**
 */
class ImportMapper
{
    /**
     * @param Import $import
     *
     * @return array
     */
    public function map(Import $import): array
    {
        return [
            'id' => $import->getId(),
            'status' => $import->getStatus(),
            'source_id' => $import->getSourceId()->getValue(),
            'transformer_id' => $import->getTransformerId()->getValue(),
        ];
    }
}
