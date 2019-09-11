<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Event;

use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;

/**
 */
class SegmentConditionSetChangedEvent implements DomainEventInterface
{
    /**
     * @var ConditionSetId
     *
     * @JMS\Type("Ergonode\Condition\Domain\ConditionSetId")
     */
    private $from;

    /**
     * @var ConditionSetId
     *
     * @JMS\Type("Ergonode\Condition\Domain\ConditionSetId")
     */
    private $to;

    /**
     * @param ConditionSetId $from
     * @param ConditionSetId $to
     */
    public function __construct(ConditionSetId $from, ConditionSetId $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return ConditionSetId
     */
    public function getFrom(): ConditionSetId
    {
        return $this->from;
    }

    /**
     * @return ConditionSetId
     */
    public function getTo(): ConditionSetId
    {
        return $this->to;
    }
}
