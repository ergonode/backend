<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Domain\Repository;

use Ergonode\Condition\Domain\Entity\ConditionSet;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;

interface ConditionSetRepositoryInterface
{
    public function load(ConditionSetId $id): ?ConditionSet;

    public function save(ConditionSet $aggregateRoot): void;

    public function exists(ConditionSetId $id): bool;

    public function delete(ConditionSet $aggregateRoot): void;
}
