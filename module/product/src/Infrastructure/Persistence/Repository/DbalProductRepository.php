<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Persistence\Repository;

use Doctrine\DBAL\DBALException;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManager;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Event\ProductDeletedEvent;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Webmozart\Assert\Assert;

class DbalProductRepository implements ProductRepositoryInterface
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
    public function load(ProductId $id): ?AbstractProduct
    {
        /** @var AbstractProduct $result */
        $result = $this->manager->load($id);
        Assert::nullOrIsInstanceOf($result, AbstractProduct::class);

        return $result;
    }

    /**
     * @param AbstractProduct $aggregateRoot
     *
     * @throws DBALException
     */
    public function save(AbstractProduct $aggregateRoot): void
    {
        $this->manager->save($aggregateRoot);
    }

    /**
     * @param ProductId $id
     *
     * @return bool
     */
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
        $this->save($aggregateRoot);

        $this->manager->delete($aggregateRoot);
    }
}
