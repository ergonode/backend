<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Infrastructure\Manager\Decorator;

use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManagerInterface;
use Ergonode\SharedKernel\Domain\AggregateId;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;

class EventStoreManagerCacheDecorator implements EventStoreManagerInterface
{
    private AdapterInterface $adapter;

    private EventStoreManagerInterface $manager;

    public function __construct(AdapterInterface $adapter, EventStoreManagerInterface $manager)
    {
        $this->adapter = $adapter;
        $this->manager = $manager;
    }

    public function load(AggregateId $id): ?AbstractAggregateRoot
    {
        $item = $this->adapter->getItem($id->getValue());
        if (!$item->isHit()) {
            $aggregate = $this->manager->load($id);
            $item->set($aggregate);
            $this->adapter->save($item);
        }

        return $item->get();
    }

    public function save(AbstractAggregateRoot $aggregateRoot): void
    {
        $this->manager->save($aggregateRoot);
        $this->adapter->deleteItem($aggregateRoot->getId()->getValue());
    }

    public function exists(AggregateId $id): bool
    {
        return $this->manager->exists($id);
    }

    public function delete(AbstractAggregateRoot $aggregateRoot): void
    {
        $this->manager->delete($aggregateRoot);
        $this->adapter->deleteItem($aggregateRoot->getId()->getValue());
    }
}
