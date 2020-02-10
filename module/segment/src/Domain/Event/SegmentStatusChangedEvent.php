<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Ergonode\Segment\Domain\ValueObject\SegmentStatus;
use JMS\Serializer\Annotation as JMS;

/**
 */
class SegmentStatusChangedEvent implements DomainEventInterface
{
    /**
     * @var SegmentId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\SegmentId")
     */
    private SegmentId $id;

    /**
     * @var SegmentStatus
     *
     * @JMS\Type("Ergonode\Segment\Domain\ValueObject\SegmentStatus")
     */
    private SegmentStatus $from;

    /**
     * @var SegmentStatus
     *
     * @JMS\Type("Ergonode\Segment\Domain\ValueObject\SegmentStatus")
     */
    private SegmentStatus $to;

    /**
     * @param SegmentId     $id
     * @param SegmentStatus $from
     * @param SegmentStatus $to
     */
    public function __construct(SegmentId $id, SegmentStatus $from, SegmentStatus $to)
    {
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return SegmentId
     */
    public function getAggregateId(): SegmentId
    {
        return $this->id;
    }

    /**
     * @return SegmentStatus
     */
    public function getFrom(): SegmentStatus
    {
        return $this->from;
    }

    /**
     * @return SegmentStatus
     */
    public function getTo(): SegmentStatus
    {
        return $this->to;
    }
}
