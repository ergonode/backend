<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Persistence\Dbal\Repository;

use Ergonode\Core\Domain\Entity\Unit;
use Ergonode\Core\Domain\Event\UnitDeletedEvent;
use Ergonode\Core\Domain\Repository\UnitRepositoryInterface;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManager;
use Webmozart\Assert\Assert;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception\InvalidArgumentException;

/**
 */
class DbalUnitRepository implements UnitRepositoryInterface
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
    public function exists(UnitId $id): bool
    {
        return $this->manager->exists($id);
    }

    /**
     * @param UnitId $id
     *
     * @return AbstractAggregateRoot|null
     *
     * @throws \ReflectionException
     */
    public function load(UnitId $id): ?AbstractAggregateRoot
    {
        $aggregate = $this->manager->load($id);
        Assert::nullOrIsInstanceOf($aggregate, Unit::class);

        return $aggregate;
    }

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     *
     * @throws DBALException
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void
    {
        $this->manager->save($aggregateRoot);
    }

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     *
     * @throws DBALException
     * @throws InvalidArgumentException
     */
    public function delete(AbstractAggregateRoot $aggregateRoot): void
    {
        $aggregateRoot->apply(new UnitDeletedEvent($aggregateRoot->getId()));
        $this->save($aggregateRoot);

        $this->manager->delete($aggregateRoot);
    }
}
