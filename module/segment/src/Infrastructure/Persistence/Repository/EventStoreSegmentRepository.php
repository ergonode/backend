<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Persistence\Repository;

use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManagerInterface;
use Ergonode\Segment\Domain\Entity\Segment;
use Ergonode\Segment\Domain\Event\SegmentDeletedEvent;
use Ergonode\Segment\Domain\Repository\SegmentRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Bus\ApplicationEventBusInterface;
use Ergonode\Segment\Application\Event\SegmentDeletedEvent as SegmentDeletedApplicationEvent;
use Ergonode\Segment\Application\Event\SegmentCreateEvent;
use Ergonode\Segment\Application\Event\SegmentUpdatedEvent;

class EventStoreSegmentRepository implements SegmentRepositoryInterface
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
    public function load(SegmentId $id): ?Segment
    {
        /** @var Segment|null $aggregate */
        $aggregate = $this->manager->load($id);
        Assert::nullOrIsInstanceOf($aggregate, Segment::class);

        return $aggregate;
    }

    /**
     * {@inheritDoc}
     */
    public function save(Segment $segment): void
    {
        $isNew = $segment->isNew();
        $this->manager->save($segment);
        if ($isNew) {
            $this->eventBus->dispatch(new SegmentCreateEvent($segment));
        } else {
            $this->eventBus->dispatch(new SegmentUpdatedEvent($segment));
        }
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
        $this->manager->save($segment);

        $this->manager->delete($segment);
        $this->eventBus->dispatch(new SegmentDeletedApplicationEvent($segment));
    }
}
