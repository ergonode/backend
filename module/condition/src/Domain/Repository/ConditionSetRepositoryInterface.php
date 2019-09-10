<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Repository;

use Ergonode\Condition\Domain\Entity\ConditionSet;
use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;

/**
 */
interface ConditionSetRepositoryInterface
{
    /**
     * @param ConditionSetId $id
     *
     * @return ConditionSet|null
     */
    public function load(ConditionSetId $id): ?AbstractAggregateRoot;

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void;

    /**
     * @param ConditionSetId $id
     *
     * @return bool
     */
    public function exists(ConditionSetId $id): bool;

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function delete(AbstractAggregateRoot $aggregateRoot): void;
}
