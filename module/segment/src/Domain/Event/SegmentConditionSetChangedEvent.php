<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;

use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;

class SegmentConditionSetChangedEvent implements AggregateEventInterface
{
    private SegmentId $id;

    private ?ConditionSetId $to;

    public function __construct(SegmentId $id, ?ConditionSetId $to = null)
    {
        $this->id = $id;
        $this->to = $to;
    }

    public function getAggregateId(): SegmentId
    {
        return $this->id;
    }

    public function getTo(): ?ConditionSetId
    {
        return $this->to;
    }
}
