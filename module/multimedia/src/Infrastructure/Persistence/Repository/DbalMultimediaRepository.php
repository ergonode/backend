<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Persistence\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManager;
use Ergonode\Multimedia\Domain\Entity\AbstractMultimedia;
use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Domain\Event\MultimediaDeletedEvent;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Webmozart\Assert\Assert;

class DbalMultimediaRepository implements MultimediaRepositoryInterface
{
    private EventStoreManager $manager;

    public function __construct(EventStoreManager $manager)
    {
        $this->manager = $manager;
    }

    /**
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

    public function save(Multimedia $aggregateRoot): void
    {
        $this->manager->save($aggregateRoot);
    }

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
