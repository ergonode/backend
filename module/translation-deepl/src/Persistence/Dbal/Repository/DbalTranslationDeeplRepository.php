<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\TranslationDeepl\Persistence\Dbal\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\DomainEventDispatcherInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventStoreInterface;
use Ergonode\TranslationDeepl\Domain\Entity\TranslationDeepl;
use Ergonode\TranslationDeepl\Domain\Entity\TranslationDeeplId;
use Ergonode\TranslationDeepl\Domain\Repository\TranslationDeeplRepositoryInterface;

/**
 */
class DbalTranslationDeeplRepository implements TranslationDeeplRepositoryInterface
{
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
     * @param TranslationDeeplId $id
     *
     * @return AbstractAggregateRoot
     * @throws \ReflectionException
     */
    public function load(TranslationDeeplId $id): ?AbstractAggregateRoot
    {
        $eventStream = $this->eventStore->load($id);
        if ($eventStream->count() > 0) {
            $class = new \ReflectionClass(TranslationDeepl::class);
            /** @var AbstractAggregateRoot $aggregate */
            $aggregate = $class->newInstanceWithoutConstructor();
            if (!$aggregate instanceof AbstractAggregateRoot) {
                throw new \LogicException(sprintf('Impossible to initialize "%s"', TranslationDeepl::class));
            }
            $aggregate->initialize($eventStream);

            return $aggregate;
        }

        return null;
    }

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void
    {
        $events = $aggregateRoot->popEvents();

        $this->eventStore->append($aggregateRoot->getId(), $events);
        foreach ($events as $envelope) {
            $this->eventDispatcher->dispatch($envelope);
        }
    }

    /**
     * @param TranslationDeeplId $id
     *
     * @return bool
     */
    public function exists(TranslationDeeplId $id) : bool
    {
        return $this->eventStore->load($id)->count() > 0;
    }
}
