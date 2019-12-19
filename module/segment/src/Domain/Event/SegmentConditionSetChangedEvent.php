<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Event;

use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainAggregateEventInterface;
use Ergonode\Segment\Domain\Entity\SegmentId;
use JMS\Serializer\Annotation as JMS;
use Zend\EventManager\Exception\DomainException;

/**
 */
class SegmentConditionSetChangedEvent implements DomainAggregateEventInterface
{
    /**
     * @var SegmentId
     *
     * @JMS\Type("Ergonode\Segment\Domain\Entity\SegmentId")
     */
    private $id;

    /**
     * @var ConditionSetId|null
     *
     * @JMS\Type("Ergonode\Condition\Domain\Entity\ConditionSetId")
     */
    private $from;

    /**
     * @var ConditionSetId|null
     *
     * @JMS\Type("Ergonode\Condition\Domain\Entity\ConditionSetId")
     */
    private $to;

    /**
     * @param SegmentId      $id
     * @param ConditionSetId $from
     * @param ConditionSetId $to
     */
    public function __construct(SegmentId $id, ?ConditionSetId $from = null, ?ConditionSetId $to = null)
    {
        if ($from === null && $to === null) {
            throw new DomainException('Condition set from and to cannot be booth null');
        }

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
     * @return ConditionSetId|null
     */
    public function getFrom(): ?ConditionSetId
    {
        return $this->from;
    }

    /**
     * @return ConditionSetId|null
     */
    public function getTo(): ?ConditionSetId
    {
        return $this->to;
    }
}
