<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Persistence\Dbal\Repository;

use Ergonode\EventSourcing\Infrastructure\DomainEventDispatcherInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventStoreInterface;
use Ergonode\Reader\Domain\Entity\Reader;
use Ergonode\Reader\Domain\Entity\ReaderId;
use Ergonode\Reader\Domain\Repository\ReaderRepositoryInterface;

/**
 */
class DbalReaderRepository implements ReaderRepositoryInterface
{
    private const TABLE = 'importer.event_store';

    /**
     * @var DomainEventStoreInterface
     */
    private $eventStore;

    /**
     * @var DomainEventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param DomainEventStoreInterface      $eventStore
     * @param DomainEventDispatcherInterface $eventDispatcher
     */
    public function __construct(DomainEventStoreInterface $eventStore, DomainEventDispatcherInterface $eventDispatcher)
    {
        $this->eventStore = $eventStore;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param ReaderId $id
     *
     * @return Reader
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
     * @param Reader $aggregateRoot
     */
    public function save(Reader $aggregateRoot): void
    {
        $events = $aggregateRoot->popEvents();

        $this->eventStore->append($aggregateRoot->getId(), $events, self::TABLE);
        foreach ($events as $envelope) {
            $this->eventDispatcher->dispatch($envelope);
        }
    }

    /**
     * @param ReaderId $id
     *
     * @return bool
     */
    public function exists(ReaderId $id) : bool
    {
        return $this->eventStore->load($id, self::TABLE)->count() > 0;
    }
}
