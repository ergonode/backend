<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Importer\Domain\Repository;

use Ergonode\Importer\Domain\Entity\AbstractImport;
use Ergonode\Importer\Domain\Entity\ImportId;

/**
 */
interface ImportRepositoryInterface
{
    /**
     * @param ImportId $id
     *
     * @return AbstractImport|null
     *
     * @throws \ReflectionException
     */
    public function load(ImportId $id): ?AbstractImport;

    /**
     * @param AbstractImport $import
     */
    public function save(AbstractImport $import): void;

    /**
     * @param ImportId $id
     *
     * @return bool
     */
    public function exists(ImportId $id): bool;
}
