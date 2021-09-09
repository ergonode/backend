<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Domain\Repository;

use Ergonode\Core\Domain\Entity\Unit;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;

interface UnitRepositoryInterface
{
    public function exists(UnitId $id): bool;

    public function load(UnitId $id): ?Unit;

    public function save(Unit $aggregateRoot): void;

    public function delete(Unit $aggregateRoot): void;
}
