<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Persistence\Repository;

use Doctrine\DBAL\DBALException;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManagerInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Event\ProductDeletedEvent;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Bus\ApplicationEventBusInterface;
use Ergonode\Product\Application\Event\ProductUpdatedEvent;
use Ergonode\Product\Application\Event\ProductCreatedEvent;
use Ergonode\Product\Application\Event\ProductDeletedEvent as ProductDeletedApplicationEvent;

class EventStoreProductRepository implements ProductRepositoryInterface
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
     *
     * @throws \ReflectionException
     */
    public function load(ProductId $id): ?AbstractProduct
    {
        /** @var AbstractProduct $result */
        $result = $this->manager->load($id);
        Assert::nullOrIsInstanceOf($result, AbstractProduct::class);

        return $result;
    }

    /**
     * @throws DBALException
     */
    public function save(AbstractProduct $aggregateRoot): void
    {
        $isNew = $aggregateRoot->isNew();

        $this->manager->save($aggregateRoot);

        if ($isNew) {
            $this->eventBus->dispatch(new ProductCreatedEvent($aggregateRoot));
        } else {
            $this->eventBus->dispatch(new ProductUpdatedEvent($aggregateRoot));
        }
    }

    public function exists(ProductId $id): bool
    {
        return $this->manager->exists($id);
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function delete(AbstractProduct $aggregateRoot): void
    {
        $aggregateRoot->apply(new ProductDeletedEvent($aggregateRoot->getId()));
        $this->manager->save($aggregateRoot);
        $this->manager->delete($aggregateRoot);
        $this->eventBus->dispatch(new ProductDeletedApplicationEvent($aggregateRoot));
    }
}
