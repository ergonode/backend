<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Persistence\Repository;

use Ergonode\Condition\Domain\Entity\ConditionSet;
use Ergonode\Condition\Domain\Event\ConditionSetDeletedEvent;
use Ergonode\Condition\Domain\Repository\ConditionSetRepositoryInterface;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManagerInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Webmozart\Assert\Assert;

class EventStoreConditionSetRepository implements ConditionSetRepositoryInterface
{
    private EventStoreManagerInterface $manager;

    public function __construct(EventStoreManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @throws \ReflectionException
     */
    public function load(ConditionSetId $id): ?ConditionSet
    {
        /** @var ConditionSet|null $aggregate */
        $aggregate = $this->manager->load($id);
        Assert::nullOrIsInstanceOf($aggregate, ConditionSet::class);

        return $aggregate;
    }

    public function save(ConditionSet $aggregateRoot): void
    {
        $this->manager->save($aggregateRoot);
    }

    public function exists(ConditionSetId $id): bool
    {

        return $this->manager->exists($id);
    }

    /**
     * @throws \Exception
     */
    public function delete(ConditionSet $aggregateRoot): void
    {
        $aggregateRoot->apply(new ConditionSetDeletedEvent($aggregateRoot->getId()));
        $this->save($aggregateRoot);

        $this->manager->delete($aggregateRoot);
    }
}
