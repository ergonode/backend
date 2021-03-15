<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Domain\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;

interface UnitRepositoryInterface
{
    public function exists(UnitId $id): bool;

    public function load(UnitId $id): ?AbstractAggregateRoot;

    public function save(AbstractAggregateRoot $aggregateRoot): void;

    public function delete(AbstractAggregateRoot $aggregateRoot): void;
}
