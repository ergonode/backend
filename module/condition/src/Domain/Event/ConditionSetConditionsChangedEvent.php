<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Domain\Event;

use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Webmozart\Assert\Assert;

class ConditionSetConditionsChangedEvent implements AggregateEventInterface
{
    private ConditionSetId $id;

    /**
     * @var ConditionInterface[]
     */
    private array $to;

    /**
     * @param ConditionInterface[] $to
     */
    public function __construct(ConditionSetId $id, array $to)
    {
        Assert::allIsInstanceOf($to, ConditionInterface::class);

        $this->id = $id;
        $this->to = $to;
    }

    public function getAggregateId(): ConditionSetId
    {
        return $this->id;
    }

    /**
     * @return ConditionInterface[]
     */
    public function getTo(): array
    {
        return $this->to;
    }
}
