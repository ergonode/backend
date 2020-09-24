<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Infrastructure\Persistence\Repository;

use Ergonode\Condition\Domain\Entity\ConditionSet;
use Ergonode\Condition\Domain\Event\ConditionSetDeletedEvent;
use Ergonode\Condition\Domain\Repository\ConditionSetRepositoryInterface;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManager;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Webmozart\Assert\Assert;

/**
 */
class DbalConditionSetRepository implements ConditionSetRepositoryInterface
{
    /**
     * @var EventStoreManager
     */
    private EventStoreManager $manager;

    /**
     * @param EventStoreManager $manager
     */
    public function __construct(EventStoreManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param ConditionSetId $id
     *
     * @return AbstractAggregateRoot|null
     *
     * @throws \ReflectionException
     */
    public function load(ConditionSetId $id): ?AbstractAggregateRoot
    {
        $aggregate = $this->manager->load($id);
        Assert::nullOrIsInstanceOf($aggregate, ConditionSet::class);

        return $aggregate;
    }

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void
    {
        $this->manager->save($aggregateRoot);
    }

    /**
     * @param ConditionSetId $id
     *
     * @return bool
     */
    public function exists(ConditionSetId $id): bool
    {

        return $this->manager->exists($id);
    }

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     *
     * @throws \Exception
     */
    public function delete(AbstractAggregateRoot $aggregateRoot): void
    {
        $aggregateRoot->apply(new ConditionSetDeletedEvent($aggregateRoot->getId()));
        $this->save($aggregateRoot);

        $this->manager->delete($aggregateRoot);
    }
}
