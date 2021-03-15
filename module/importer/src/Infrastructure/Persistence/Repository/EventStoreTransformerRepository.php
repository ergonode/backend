<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Persistence\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManagerInterface;
use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;
use Ergonode\Importer\Domain\Entity\Transformer;
use Ergonode\Importer\Domain\Event\TransformerDeletedEvent;
use Ergonode\Importer\Domain\Repository\TransformerRepositoryInterface;
use Webmozart\Assert\Assert;

class EventStoreTransformerRepository implements TransformerRepositoryInterface
{
    private EventStoreManagerInterface $manager;

    public function __construct(EventStoreManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \ReflectionException
     */
    public function load(TransformerId $id): ?AbstractAggregateRoot
    {
        $aggregate = $this->manager->load($id);
        Assert::nullOrIsInstanceOf($aggregate, Transformer::class);

        return $aggregate;
    }

    /**
     * {@inheritDoc}
     */
    public function exists(TransformerId $id): bool
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
        $aggregateRoot->apply(new TransformerDeletedEvent($aggregateRoot->getId()));
        $this->save($aggregateRoot);

        $this->manager->delete($aggregateRoot);
    }
}
