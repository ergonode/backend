<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Event\Transition;

use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TransitionConditionSetChangedEvent implements DomainEventInterface
{
    /**
     * @var ConditionSetId|null
     *
     * @JMS\Type("Ergonode\Condition\Domain\Entity\ConditionSetId")
     */
    private $conditionSetId;

    /**
     * @param ConditionSetId|null $conditionSetId
     */
    public function __construct(?ConditionSetId $conditionSetId = null)
    {
        $this->conditionSetId = $conditionSetId;
    }

    /**
     * @return ConditionSetId|null
     */
    public function getConditionSetId(): ?ConditionSetId
    {
        return $this->conditionSetId;
    }
}
