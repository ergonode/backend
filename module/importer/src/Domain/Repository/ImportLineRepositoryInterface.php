<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Importer\Domain\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\DBALException;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\Importer\Domain\Entity\ImportLine;

/**
 */
interface ImportLineRepositoryInterface
{
    /**
     * @param ImportLine $importLine
     *
     * @throws DBALException
     */
    public function save(ImportLine $importLine): void;

    /**
     * @param ImportId $importId
     * @param int      $step
     * @param int      $line
     *
     * @return ImportLine|null
     */
    public function load(ImportId $importId, int $step, int $line): ?ImportLine;

    /**
     * @param ImportId $importId
     *
     * @return ArrayCollection|ImportLine[]
     */
    public function findCollectionByImport(ImportId $importId): ArrayCollection;

    /**
     * @param ImportId $importId
     *
     * @return array
     */
    public function getKeys(ImportId $importId): array;
}
