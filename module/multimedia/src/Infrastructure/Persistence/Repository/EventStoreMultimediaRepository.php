<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Infrastructure\Persistence\Repository;

use Doctrine\DBAL\DBALException;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManagerInterface;
use Ergonode\Multimedia\Domain\Entity\AbstractMultimedia;
use Ergonode\Multimedia\Domain\Event\MultimediaDeletedEvent;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Webmozart\Assert\Assert;

class EventStoreMultimediaRepository implements MultimediaRepositoryInterface
{
    private EventStoreManagerInterface $manager;

    public function __construct(EventStoreManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @throws \ReflectionException
     */
    public function load(MultimediaId $id): ?AbstractMultimedia
    {
        /** @var AbstractMultimedia|null $aggregate */
        $aggregate = $this->manager->load($id);
        Assert::nullOrIsInstanceOf($aggregate, AbstractMultimedia::class);

        return $aggregate;
    }

    /**
     * @throws DBALException
     */
    public function save(AbstractMultimedia $aggregateRoot): void
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
    public function delete(AbstractMultimedia $multimedia): void
    {
        $multimedia->apply(new MultimediaDeletedEvent($multimedia->getId()));
        $this->save($multimedia);

        $this->manager->delete($multimedia);
    }
}
