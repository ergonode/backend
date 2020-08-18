<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Persistence\Dbal\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Ergonode\Multimedia\Domain\Event\MultimediaDeletedEvent;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManager;
use Webmozart\Assert\Assert;
use Ergonode\Multimedia\Domain\Entity\AbstractMultimedia;

/**
 */
class DbalMultimediaRepository implements MultimediaRepositoryInterface
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
     * @param MultimediaId $id
     *
     * @return Multimedia|null
     *
     * @throws \ReflectionException
     */
    public function load(MultimediaId $id): ?AbstractAggregateRoot
    {
        $aggregate = $this->manager->load($id);
        Assert::nullOrIsInstanceOf($aggregate, AbstractMultimedia::class);

        return $aggregate;
    }

    /**
     * @param Multimedia $aggregateRoot
     */
    public function save(Multimedia $aggregateRoot): void
    {
        $this->manager->save($aggregateRoot);
    }

    /**
     * @param MultimediaId $id
     *
     * @return bool
     */
    public function exists(MultimediaId $id): bool
    {
        return $this->manager->exists($id);
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function delete(Multimedia $multimedia): void
    {
        $multimedia->apply(new MultimediaDeletedEvent($multimedia->getId()));
        $this->save($multimedia);

        $this->manager->delete($multimedia);
    }
}
