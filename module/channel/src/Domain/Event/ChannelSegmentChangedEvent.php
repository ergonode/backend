<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ChannelSegmentChangedEvent implements DomainEventInterface
{
    /**
     * @var ChannelId
     *
     * @JMS\Type("Ergonode\Workflow\Domain\Entity\ChannelId")
     */
    private ChannelId $id;

    /**
     * @var SegmentId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\SegmentId")
     */
    private SegmentId $from;

    /**
     * @var SegmentId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\SegmentId")
     */
    private SegmentId $to;

    /**
     * @param ChannelId $id
     * @param SegmentId $from
     * @param SegmentId $to
     */
    public function __construct(ChannelId $id, SegmentId $from, SegmentId $to)
    {
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return ChannelId
     */
    public function getAggregateId(): ChannelId
    {
        return $this->id;
    }

    /**
     * @return SegmentId
     */
    public function getFrom(): SegmentId
    {
        return $this->from;
    }

    /**
     * @return SegmentId
     */
    public function getTo(): SegmentId
    {
        return $this->to;
    }
}
