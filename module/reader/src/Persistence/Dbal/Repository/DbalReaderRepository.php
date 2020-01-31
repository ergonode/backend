<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Persistence\Dbal\Repository;

use Ergonode\EventSourcing\Infrastructure\Bus\EventBusInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventStoreInterface;
use Ergonode\Reader\Domain\Entity\Reader;
use Ergonode\Reader\Domain\Entity\ReaderId;
use Ergonode\Reader\Domain\Event\ReaderDeletedEvent;
use Ergonode\Reader\Domain\Repository\ReaderRepositoryInterface;

/**
 */
class DbalReaderRepository implements ReaderRepositoryInterface
{
    private const TABLE = 'importer.event_store';

    /**
     * @var DomainEventStoreInterface
     */
    private DomainEventStoreInterface $eventStore;

    /**
     * @var EventBusInterface
     */
    private EventBusInterface $eventBus;

    /**
     * @param DomainEventStoreInterface $eventStore
     * @param EventBusInterface         $eventBus
     */
    public function __construct(DomainEventStoreInterface $eventStore, EventBusInterface $eventBus)
    {
        $this->eventStore = $eventStore;
        $this->eventBus = $eventBus;
    }

    /**
     * {@inheritDoc}
     */
    public function exists(ReaderId $id) : bool
    {
        return $this->eventStore->load($id, self::TABLE)->count() > 0;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \ReflectionException
     */
    public function load(ReaderId $id): ?Reader
    {
        $eventStream = $this->eventStore->load($id, self::TABLE);

        if (\count($eventStream) > 0) {
            $class = new \ReflectionClass(Reader::class);
            /** @var Reader $aggregate */
            $aggregate = $class->newInstanceWithoutConstructor();
            if (!$aggregate instanceof Reader) {
                throw new \LogicException(sprintf('Impossible to initialize "%s"', $class));
            }

            $aggregate->initialize($eventStream);

            return $aggregate;
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function save(Reader $aggregateRoot): void
    {
        $events = $aggregateRoot->popEvents();

        $this->eventStore->append($aggregateRoot->getId(), $events, self::TABLE);
        foreach ($events as $envelope) {
            $this->eventBus->dispatch($envelope->getEvent());
        }
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function delete(Reader $reader): void
    {
        $reader->apply(new ReaderDeletedEvent($reader->getId()));
        $this->save($reader);

        $this->eventStore->delete($reader->getId());
    }
}
