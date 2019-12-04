<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Entity;

use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Condition\Domain\Event\ConditionSetConditionsChangedEvent;
use Ergonode\Condition\Domain\Event\ConditionSetCreatedEvent;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

/**
 * @JMS\ExclusionPolicy("all")
 */
class ConditionSet extends AbstractAggregateRoot
{
    /**
     * @var ConditionSetId
     *
     * @JMS\Type("Ergonode\Condition\Domain\Entity\ConditionSetId")
     * @JMS\Expose()
     */
    private $id;

    /**
     * @var ConditionInterface[]
     *
     * @JMS\Type("array<Ergonode\Condition\Domain\ConditionInterface>")
     * @JMS\Expose()
     */
    private $conditions;

    /**
     * @param ConditionSetId $id
     * @param array          $conditions
     *
     * @throws \Exception
     */
    public function __construct(
        ConditionSetId $id,
        array $conditions = []
    ) {
        Assert::allIsInstanceOf($conditions, ConditionInterface::class);

        $this->apply(new ConditionSetCreatedEvent($id, $conditions));
    }

    /**
     * @return ConditionSetId|AbstractId
     */
    public function getId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return ConditionInterface[]
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /**
     * @param array $conditions
     *
     * @throws \Exception
     */
    public function changeConditions(array $conditions): void
    {
        if (sha1(serialize($this->conditions)) !== sha1(serialize($conditions))) {
            $this->apply(new ConditionSetConditionsChangedEvent($this->conditions, $conditions));
        }
    }

    /**
     * @param ConditionSetCreatedEvent $event
     */
    protected function applyConditionSetCreatedEvent(ConditionSetCreatedEvent $event): void
    {
        $this->id = $event->getId();
        $this->conditions = $event->getConditions();
    }

    /**
     * @param ConditionSetConditionsChangedEvent $event
     */
    protected function applyConditionSetConditionsChangedEvent(ConditionSetConditionsChangedEvent $event): void
    {
        $this->conditions = $event->getTo();
    }
}
