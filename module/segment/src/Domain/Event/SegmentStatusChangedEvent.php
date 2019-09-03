<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Segment\Domain\ValueObject\SegmentStatus;
use JMS\Serializer\Annotation as JMS;

/**
 */
class SegmentStatusChangedEvent implements DomainEventInterface
{
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
     * @param SegmentStatus $from
     * @param SegmentStatus $to
     */
    public function __construct(SegmentStatus $from, SegmentStatus $to)
    {
        $this->from = $from;
        $this->to = $to;
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
