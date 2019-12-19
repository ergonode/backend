<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Event;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainAggregateEventInterface;
use Ergonode\Segment\Domain\Entity\SegmentId;
use Ergonode\Segment\Domain\ValueObject\SegmentStatus;
use JMS\Serializer\Annotation as JMS;

/**
 */
class SegmentStatusChangedEvent implements DomainAggregateEventInterface
{
    /**
     * @var SegmentId
     *
     * @JMS\Type("Ergonode\Segment\Domain\Entity\SegmentId")
     */
    private $id;

    /**
     * @var SegmentStatus
     *
     * @JMS\Type("Ergonode\Segment\Domain\ValueObject\SegmentStatus")
     */
    private $from;

    /**
     * @var SegmentStatus
     *
     * @JMS\Type("Ergonode\Segment\Domain\ValueObject\SegmentStatus")
     */
    private $to;

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
    public function getAggregateId(): AbstractId
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
