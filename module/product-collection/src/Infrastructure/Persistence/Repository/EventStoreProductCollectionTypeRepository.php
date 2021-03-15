<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Infrastructure\Persistence\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManagerInterface;
use Ergonode\ProductCollection\Domain\Entity\ProductCollectionType;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionTypeDeletedEvent;
use Ergonode\ProductCollection\Domain\Repository\ProductCollectionTypeRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use Webmozart\Assert\Assert;

class EventStoreProductCollectionTypeRepository implements ProductCollectionTypeRepositoryInterface
{
    private EventStoreManagerInterface $manager;

    public function __construct(EventStoreManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritDoc}
     */
    public function exists(ProductCollectionTypeId $id): bool
    {
        return $this->manager->exists($id);
    }

    /**
     * @throws \ReflectionException
     */
    public function load(ProductCollectionTypeId $id): ?AbstractAggregateRoot
    {
        $aggregate = $this->manager->load($id);
        Assert::nullOrIsInstanceOf($aggregate, ProductCollectionType::class);

        return $aggregate;
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
        $aggregateRoot->apply(new ProductCollectionTypeDeletedEvent($aggregateRoot->getId()));
        $this->save($aggregateRoot);

        $this->manager->delete($aggregateRoot);
    }
}
