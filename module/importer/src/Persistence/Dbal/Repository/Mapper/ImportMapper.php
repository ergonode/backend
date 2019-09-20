<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Persistence\Dbal\Repository\Mapper;

use Ergonode\Importer\Domain\Entity\AbstractImport;

/**
 * Class ImportMapper
 */
class ImportMapper
{
    /**
     * @param AbstractImport $import
     *
     * @return array
     */
    public function map(AbstractImport $import): array
    {
        return [
            'id' => $import->getId(),
            'name' => $import->getName(),
            'status' => $import->getStatus(),
            'options' => \json_encode($import->getOptions()),
            'type' => \get_class($import),
            'reason' => $import->getReason(),
        ];
    }
}
