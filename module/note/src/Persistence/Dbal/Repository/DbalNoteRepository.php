<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Note\Persistence\Dbal\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\DomainEventDispatcherInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventStoreInterface;
use Ergonode\Note\Domain\Entity\Note;
use Ergonode\Note\Domain\Entity\NoteId;
use Ergonode\Note\Domain\Event\NoteDeletedEvent;
use Ergonode\Note\Domain\Repository\NoteRepositoryInterface;

/**
 */
class DbalNoteRepository implements NoteRepositoryInterface
{
    /**
     * @var DomainEventStoreInterface $store
     */
    private $store;

    /**
     * @var DomainEventDispatcherInterface $dispatcher
     */
    private $dispatcher;

    /**
     * @param DomainEventStoreInterface      $store
     * @param DomainEventDispatcherInterface $dispatcher
     */
    public function __construct(DomainEventStoreInterface $store, DomainEventDispatcherInterface $dispatcher)
    {
        $this->store = $store;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param NoteId $id
     *
     * @return AbstractAggregateRoot|null
     *
     * @throws \ReflectionException
     */
    public function load(NoteId $id): ?AbstractAggregateRoot
    {
        $stream = $this->store->load($id);
        if ($stream->count() > 0) {
            $class = new \ReflectionClass(Note::class);
            $aggregate = $class->newInstanceWithoutConstructor();
            if (!$aggregate instanceof AbstractAggregateRoot) {
                throw new \LogicException(sprintf('Impossible to initialize "%s"', Note::class));
            }
            $aggregate->initialize($stream);

            return $aggregate;
        }

        return null;
    }

    /**
     * @param Note $object
     */
    public function save(Note $object): void
    {
        $events = $object->popEvents();
        $this->store->append($object->getId(), $events);
        foreach ($events as $envelope) {
            $this->dispatcher->dispatch($envelope);
        }
    }

    /**
     * @param NoteId $id
     *
     * @return bool
     */
    public function exists(NoteId $id): bool
    {
        return $this->store->load($id)->count() > 0;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function delete(Note $object): void
    {
        $object->apply(new NoteDeletedEvent());
        $this->save($object);

        $this->store->delete($object->getId());
    }
}
