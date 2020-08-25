<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Importer\Domain\Repository;

use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;

/**
 */
interface ImportRepositoryInterface
{
    /**
     * @param ImportId $id
     *
     * @return Import|null
     *
     * @throws \ReflectionException
     */
    public function load(ImportId $id): ?Import;

    /**
     * @param Import $import
     */
    public function save(Import $import): void;

    /**
     * @param ImportId $id
     *
     * @return bool
     */
    public function exists(ImportId $id): bool;

    /**
     * @param Import $import
     */
    public function delete(Import $import): void;
}
