<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Event\Transition;

use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Workflow\Domain\Entity\TransitionId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TransitionConditionSetChangedEvent implements DomainEventInterface
{
    /**
     * @var TransitionId
     *
     * @JMS\Type("Ergonode\Workflow\Domain\Entity\TransitionId")
     */
    private $transitionId;

    /**
     * @var ConditionSetId|null
     *
     * @JMS\Type("Ergonode\Condition\Domain\Entity\ConditionSetId")
     */
    private $conditionSetId;

    /**
     * @param TransitionId        $transitionId
     * @param ConditionSetId|null $conditionSetId
     */
    public function __construct(TransitionId $transitionId, ?ConditionSetId $conditionSetId = null)
    {
        $this->transitionId = $transitionId;
        $this->conditionSetId = $conditionSetId;
    }

    /**
     * @return TransitionId
     */
    public function getTransitionId(): TransitionId
    {
        return $this->transitionId;
    }

    /**
     * @return ConditionSetId|null
     */
    public function getConditionSetId(): ?ConditionSetId
    {
        return $this->conditionSetId;
    }
}
