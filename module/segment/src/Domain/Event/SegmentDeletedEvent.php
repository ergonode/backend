<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;

class SegmentDeletedEvent extends AbstractDeleteEvent
{
    private SegmentId $id;

    public function __construct(SegmentId $id)
    {
        $this->id = $id;
    }

    public function getAggregateId(): SegmentId
    {
        return $this->id;
    }
}
