<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Domain\Entity;

use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Condition\Domain\Event\ConditionSetConditionsChangedEvent;
use Ergonode\Condition\Domain\Event\ConditionSetCreatedEvent;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Webmozart\Assert\Assert;

class ConditionSet extends AbstractAggregateRoot
{
    private ConditionSetId $id;

    /**
     * @var ConditionInterface[]
     */
    private array $conditions;

    /**
     * @param ConditionInterface[] $conditions
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

    public function getId(): ConditionSetId
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
            $this->apply(new ConditionSetConditionsChangedEvent($this->id, $conditions));
        }
    }

    protected function applyConditionSetCreatedEvent(ConditionSetCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->conditions = $event->getConditions();
    }

    protected function applyConditionSetConditionsChangedEvent(ConditionSetConditionsChangedEvent $event): void
    {
        $this->conditions = $event->getTo();
    }
}
