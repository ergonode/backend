<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Repository;

use Ergonode\Condition\Domain\Entity\ConditionSet;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;

interface ConditionSetRepositoryInterface
{
    /**
     * @return ConditionSet|null
     */
    public function load(ConditionSetId $id): ?AbstractAggregateRoot;

    public function save(AbstractAggregateRoot $aggregateRoot): void;

    public function exists(ConditionSetId $id): bool;

    public function delete(AbstractAggregateRoot $aggregateRoot): void;
}
