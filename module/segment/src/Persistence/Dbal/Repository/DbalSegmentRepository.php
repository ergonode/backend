<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Persistence\Dbal\Repository;

use Ergonode\Segment\Domain\Entity\Segment;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Ergonode\Segment\Domain\Event\SegmentDeletedEvent;
use Ergonode\Segment\Domain\Repository\SegmentRepositoryInterface;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManager;
use Webmozart\Assert\Assert;

/**
 */
class DbalSegmentRepository implements SegmentRepositoryInterface
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
    public function load(SegmentId $id): ?Segment
    {
        $aggregate = $this->manager->load($id);
        Assert::nullOrIsInstanceOf($aggregate, Segment::class);

        return $aggregate;
    }

    /**
     * {@inheritDoc}
     */
    public function save(Segment $segment): void
    {
        $this->manager->save($segment);
    }

    /**
     * {@inheritDoc}
     */
    public function exists(SegmentId $id): bool
    {
        return $this->manager->exists($id);
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function delete(Segment $segment): void
    {
        $segment->apply(new SegmentDeletedEvent($segment->getId()));
        $this->save($segment);

        $this->manager->delete($segment);
    }
}
