<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Persistence\Dbal\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\DomainEventDispatcherInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventStoreInterface;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Transformer\Domain\Entity\TransformerId;
use Ergonode\Transformer\Domain\Repository\TransformerRepositoryInterface;

/**
 */
class DbalTransformerRepository implements TransformerRepositoryInterface
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
     * @param TransformerId $id
     *
     * @return null|Transformer
     *
     * @throws \ReflectionException
     */
    public function load(TransformerId $id): ?AbstractAggregateRoot
    {
        $eventStream = $this->eventStore->load($id, self::TABLE);
        if ($eventStream->count() > 0) {
            $class = new \ReflectionClass(Transformer::class);
            /** @var AbstractAggregateRoot $aggregate */
            $aggregate = $class->newInstanceWithoutConstructor();
            if (!$aggregate instanceof AbstractAggregateRoot) {
                throw new \LogicException(sprintf('Impossible to initialize "%s"', Transformer::class));
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

        $this->eventStore->append($aggregateRoot->getId(), $events, self::TABLE);
        foreach ($events as $envelope) {
            $this->eventDispatcher->dispatch($envelope);
        }
    }
}
