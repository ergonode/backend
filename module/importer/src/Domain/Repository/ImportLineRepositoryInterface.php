<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Importer\Domain\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\DBALException;
use Ergonode\Importer\Domain\Entity\ImportId;
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
     * @return ArrayCollection|ImportLine[]
     */
    public function findCollectionByImport(ImportId $importId): ArrayCollection;
}
