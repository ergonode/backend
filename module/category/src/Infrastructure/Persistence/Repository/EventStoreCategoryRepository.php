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
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManagerInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Bus\ApplicationEventBusInterface;
use Ergonode\Category\Application\Event\CategoryCreateEvent;
use Ergonode\Category\Application\Event\CategoryUpdatedEvent;
use Ergonode\Category\Application\Event\CategoryDeletedEvent as CategoryDeletedApplicationEvent;

class EventStoreCategoryRepository implements CategoryRepositoryInterface
{
    private EventStoreManagerInterface $manager;

    protected ApplicationEventBusInterface $eventBus;

    public function __construct(EventStoreManagerInterface $manager, ApplicationEventBusInterface $eventBus)
    {
        $this->manager = $manager;
        $this->eventBus = $eventBus;
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
        $isNew = $aggregateRoot->isNew();

        $this->manager->save($aggregateRoot);

        if ($isNew) {
            $this->eventBus->dispatch(new CategoryCreateEvent($aggregateRoot));
        } else {
            $this->eventBus->dispatch(new CategoryUpdatedEvent($aggregateRoot));
        }
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function delete(AbstractCategory $category): void
    {
        $category->apply(new CategoryDeletedEvent($category->getId()));
        $this->manager->save($category);

        $this->manager->delete($category);
        $this->eventBus->dispatch(new CategoryDeletedApplicationEvent($category));
    }
}
