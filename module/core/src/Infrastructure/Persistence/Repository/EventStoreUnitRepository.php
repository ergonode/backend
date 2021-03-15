<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Persistence\Repository;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Ergonode\Core\Domain\Entity\Unit;
use Ergonode\Core\Domain\Event\UnitDeletedEvent;
use Ergonode\Core\Domain\Repository\UnitRepositoryInterface;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManagerInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use Webmozart\Assert\Assert;

class EventStoreUnitRepository implements UnitRepositoryInterface
{
    private EventStoreManagerInterface $manager;

    public function __construct(EventStoreManagerInterface $manager)
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
     * @throws \ReflectionException
     */
    public function load(UnitId $id): ?AbstractAggregateRoot
    {
        $aggregate = $this->manager->load($id);
        Assert::nullOrIsInstanceOf($aggregate, Unit::class);

        return $aggregate;
    }

    /**
     * @throws DBALException
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void
    {
        $this->manager->save($aggregateRoot);
    }

    /**
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
