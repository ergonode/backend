<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Persistence\Dbal\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\Bus\EventBusInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventStoreInterface;
use Ergonode\Comment\Domain\Entity\Comment;
use Ergonode\Comment\Domain\Entity\CommentId;
use Ergonode\Comment\Domain\Event\CommentDeletedEvent;
use Ergonode\Comment\Domain\Repository\CommentRepositoryInterface;

/**
 */
class DbalCommentRepository implements CommentRepositoryInterface
{
    /**
     * @var DomainEventStoreInterface
     */
    private $store;

    /**
     * @var EventBusInterface
     */
    private $eventBus;

    /**
     * @param DomainEventStoreInterface $store
     * @param EventBusInterface         $eventBus
     */
    public function __construct(
        DomainEventStoreInterface $store,
        EventBusInterface $eventBus
    ) {
        $this->store = $store;
        $this->eventBus = $eventBus;
    }


    /**
     * @param CommentId $id
     *
     * @return AbstractAggregateRoot|null
     *
     * @throws \ReflectionException
     */
    public function load(CommentId $id): ?AbstractAggregateRoot
    {
        $stream = $this->store->load($id);
        if ($stream->count() > 0) {
            $class = new \ReflectionClass(Comment::class);
            $aggregate = $class->newInstanceWithoutConstructor();
            if (!$aggregate instanceof AbstractAggregateRoot) {
                throw new \LogicException(sprintf('Impossible to initialize "%s"', Comment::class));
            }
            $aggregate->initialize($stream);

            return $aggregate;
        }

        return null;
    }

    /**
     * @param Comment $object
     */
    public function save(Comment $object): void
    {
        $events = $object->popEvents();
        $this->store->append($object->getId(), $events);
        foreach ($events as $envelope) {
            $this->eventBus->dispatch($envelope->getEvent());
        }
    }

    /**
     * @param CommentId $id
     *
     * @return bool
     */
    public function exists(CommentId $id): bool
    {
        return $this->store->load($id)->count() > 0;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function delete(Comment $object): void
    {
        $object->apply(new CommentDeletedEvent($object->getId()));
        $this->save($object);

        $this->store->delete($object->getId());
    }
}
