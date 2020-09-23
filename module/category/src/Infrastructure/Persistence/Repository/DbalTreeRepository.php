<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Infrastructure\Persistence\Repository;

use Ergonode\Category\Domain\Entity\CategoryTree;
use Ergonode\Category\Domain\Event\Tree\CategoryTreeDeletedEvent;
use Ergonode\Category\Domain\Repository\TreeRepositoryInterface;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManager;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Webmozart\Assert\Assert;

/**
 */
class DbalTreeRepository implements TreeRepositoryInterface
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
     * {@inheritDoc}
     *
     * @throws \ReflectionException
     */
    public function load(CategoryTreeId $id): ?AbstractAggregateRoot
    {
        /** @var CategoryTree $result */
        $result = $this->manager->load($id);
        Assert::nullOrIsInstanceOf($result, CategoryTree::class);

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function exists(CategoryTreeId $id) : bool
    {
        return $this->manager->exists($id);
    }

    /**
     * {@inheritDoc}
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void
    {
        $this->manager->save($aggregateRoot);
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function delete(AbstractAggregateRoot $aggregateRoot): void
    {
        $aggregateRoot->apply(new CategoryTreeDeletedEvent($aggregateRoot->getId()));
        $this->save($aggregateRoot);

        $this->manager->delete($aggregateRoot);
    }
}
