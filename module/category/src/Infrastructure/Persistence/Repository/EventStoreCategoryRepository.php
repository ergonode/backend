<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Infrastructure\Persistence\Repository;

use Ergonode\Category\Domain\Entity\AbstractCategory;
use Ergonode\Category\Domain\Event\CategoryDeletedEvent;
use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManager;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Webmozart\Assert\Assert;

class EventStoreCategoryRepository implements CategoryRepositoryInterface
{
    private EventStoreManager $manager;

    public function __construct(EventStoreManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritDoc}
     */
    public function exists(CategoryId $id): bool
    {
        return $this->manager->exists($id);
    }

    /**
     * {@inheritDoc}
     *
     * @throws \ReflectionException
     */
    public function load(CategoryId $id): ?AbstractCategory
    {
        /** @var AbstractCategory $result */
        $result = $this->manager->load($id);
        Assert::nullOrIsInstanceOf($result, AbstractCategory::class);

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function save(AbstractCategory $aggregateRoot): void
    {
        $this->manager->save($aggregateRoot);
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function delete(AbstractCategory $category): void
    {
        $category->apply(new CategoryDeletedEvent($category->getId()));
        $this->save($category);

        $this->manager->delete($category);
    }
}
