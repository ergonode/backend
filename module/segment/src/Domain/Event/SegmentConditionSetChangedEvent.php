<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use JMS\Serializer\Annotation as JMS;
use Zend\EventManager\Exception\DomainException;

class SegmentConditionSetChangedEvent implements DomainEventInterface
{
    /**
     * @var SegmentId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\SegmentId")
     */
    private SegmentId $id;

    /**
     * @var ConditionSetId|null
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId")
     */
    private ?ConditionSetId $from;

    /**
     * @var ConditionSetId|null
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId")
     */
    private ?ConditionSetId $to;

    /**
     * @param SegmentId      $id
     * @param ConditionSetId $from
     * @param ConditionSetId $to
     */
    public function __construct(SegmentId $id, ?ConditionSetId $from = null, ?ConditionSetId $to = null)
    {
        if (null === $from && null === $to) {
            throw new DomainException('Condition set from and to cannot be booth null');
        }

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
