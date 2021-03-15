<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Repository;

use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;

interface SourceRepositoryInterface
{
    /**
     * @throws \ReflectionException
     */
    public function load(SourceId $id): ?AbstractSource;

    public function save(AbstractSource $import): void;

    public function exists(SourceId $id): bool;

    public function delete(AbstractSource $import): void;
}
