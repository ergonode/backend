<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Event;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\AbstractDeleteEvent;
use Ergonode\Segment\Domain\Entity\SegmentId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class SegmentDeletedEvent extends AbstractDeleteEvent
{
    /**
     * @var SegmentId
     *
     * @JMS\Type("Ergonode\Segment\Domain\Entity\SegmentId")
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
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }
}
