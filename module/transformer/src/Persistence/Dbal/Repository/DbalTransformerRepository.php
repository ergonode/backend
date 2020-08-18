<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Persistence\Dbal\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;
use Ergonode\Transformer\Domain\Event\TransformerDeletedEvent;
use Ergonode\Transformer\Domain\Repository\TransformerRepositoryInterface;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManager;
use Webmozart\Assert\Assert;

/**
 */
class DbalTransformerRepository implements TransformerRepositoryInterface
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
