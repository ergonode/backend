<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Repository;

use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;

interface ImportRepositoryInterface
{
    /**
     * @throws \ReflectionException
     */
    public function load(ImportId $id): ?Import;

    public function save(Import $import): void;

    public function exists(ImportId $id): bool;

    public function delete(Import $import): void;

    /**
     * @param string[] $parameters
     */
    public function addError(ImportId $importId, string $message, array $parameters = []): void;
}
