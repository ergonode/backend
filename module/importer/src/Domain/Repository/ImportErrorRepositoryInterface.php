<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Importer\Domain\Repository;

use Doctrine\DBAL\DBALException;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\Importer\Domain\Entity\ImportError;

/**
 */
interface ImportErrorRepositoryInterface
{
    /**
     * @param ImportError $importLine
     *
     * @throws DBALException
     */
    public function save(ImportError $importLine): void;

    /**
     * @param ImportId $importId
     * @param int      $step
     * @param int      $line
     *
     * @return ImportError|null
     */
    public function load(ImportId $importId, int $step, int $line): ?ImportError;
}
