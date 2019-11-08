<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Event;

use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;
use Zend\EventManager\Exception\DomainException;

/**
 */
class SegmentConditionSetChangedEvent implements DomainEventInterface
{
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
     * @param ConditionSetId $from
     * @param ConditionSetId $to
     */
    public function __construct(?ConditionSetId $from = null, ?ConditionSetId $to = null)
    {
        if ($from === null && $to === null) {
            throw new DomainException('Condition set from and to cannot be booth null');
        }

        $this->from = $from;
        $this->to = $to;
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
