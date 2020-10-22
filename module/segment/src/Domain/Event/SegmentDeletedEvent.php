<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use JMS\Serializer\Annotation as JMS;

class SegmentDeletedEvent extends AbstractDeleteEvent
{
    /**
     * @var SegmentId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\SegmentId")
     */
    private SegmentId $id;

    /**
     * @param SegmentId $id
     */
    public function __construct(SegmentId $id)
    {
        $this->id = $id;
    }

    /**
     * @return SegmentId
     */
    public function getAggregateId(): SegmentId
    {
        return $this->id;
    }
}
