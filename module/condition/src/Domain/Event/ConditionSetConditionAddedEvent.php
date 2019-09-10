<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Event;

use Ergonode\Condition\Domain\Condition\ConditionInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ConditionSetConditionAddedEvent implements DomainEventInterface
{
    /**
     * @var ConditionInterface
     *
     * @JMS\Type("")
     */
    private $conditions;

    /**
     * @param ConditionInterface $conditions
     */
    public function __construct(ConditionInterface $conditions)
    {
        $this->conditions = $conditions;
    }

    /**
     * @return ConditionInterface
     */
    public function getCondition(): ConditionInterface
    {
        return $this->conditions;
    }
}
