<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\Condition\Domain\ConditionInterface;

class ConditionSetCreatedEvent implements AggregateEventInterface
{
    private ConditionSetId $id;

    /**
     * @var ConditionInterface[]
     */
    private array $conditions;

    /**
     * @param array $conditions
     */
    public function __construct(ConditionSetId $id, array $conditions = [])
    {
        $this->id = $id;
        $this->conditions = $conditions;
    }

    public function getAggregateId(): ConditionSetId
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }
}
