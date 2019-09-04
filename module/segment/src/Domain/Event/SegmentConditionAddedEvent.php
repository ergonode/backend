<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Event;

use Ergonode\Segment\Domain\Condition\ConditionInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class SegmentConditionAddedEvent implements DomainEventInterface
{
    /**
     * @var ConditionInterface
     *
     * @JMS\Type("Ergonode\Segment\Infrastructure\Condition\AbstractChannelCondition")
     */
    private $specification;

    /**
     * @param ConditionInterface $specification
     */
    public function __construct(ConditionInterface $specification)
    {
        $this->specification = $specification;
    }

    /**
     * @return ConditionInterface
     */
    public function getCondition(): ConditionInterface
    {
        return $this->specification;
    }
}
