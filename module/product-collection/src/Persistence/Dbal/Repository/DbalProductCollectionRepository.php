<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Persistence\Dbal\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\ProductCollection\Domain\Entity\ProductCollection;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionDeletedEvent;
use Ergonode\ProductCollection\Domain\Repository\ProductCollectionRepositoryInterface;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManager;
use Webmozart\Assert\Assert;

/**
 */
class DbalProductCollectionRepository implements ProductCollectionRepositoryInterface
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
     */
    public function exists(ProductCollectionId $id): bool
    {
        return $this->manager->exists($id);
    }

    /**
     * @param ProductCollectionId $id
     *
     * @return AbstractAggregateRoot|null
     *
     * @throws \ReflectionException
     */
    public function load(ProductCollectionId $id): ?AbstractAggregateRoot
    {
        $aggregate = $this->manager->load($id);
        Assert::nullOrIsInstanceOf($aggregate, ProductCollection::class);

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
        $aggregateRoot->apply(new ProductCollectionDeletedEvent($aggregateRoot->getId()));
        $this->save($aggregateRoot);

        $this->manager->delete($aggregateRoot);
    }
}
