<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Persistance\Provider;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Domain\Factory\EventStreamAggregateRootFactory;
use Ergonode\EventSourcing\Infrastructure\DomainEventDispatcherInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventFactoryInterface;
use Ergonode\EventSourcing\Infrastructure\Stream\DomainEventStream;
use Ergonode\EventSourcing\Persistance\Dbal\Repository\EventStoreRepositoryInterface;
use Ergonode\EventSourcing\Persistance\Exception\EventStreamEmptyException;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 */
class AggregateRootProvider implements AggregateRootProviderInterface
{
    private const CACHE_KEY = 'agr_%s';

    /**
     * @var EventStoreRepositoryInterface
     */
    private $eventStoreRepository;

    /**
     * @var DomainEventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var DomainEventFactoryInterface
     */
    private $domainEventFactory;

    /**
     * @var EventStreamAggregateRootFactory
     */
    private $aggregateRootFactory;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var AdapterInterface
     */
    private $cache;

    /**
     * @param EventStoreRepositoryInterface   $eventStoreRepository
     * @param DomainEventDispatcherInterface  $eventDispatcher
     * @param DomainEventFactoryInterface     $domainEventFactory
     * @param EventStreamAggregateRootFactory $aggregateRootFactory
     * @param TokenStorageInterface           $tokenStorage
     * @param AdapterInterface                $cache
     */
    public function __construct(
        EventStoreRepositoryInterface $eventStoreRepository,
        DomainEventDispatcherInterface $eventDispatcher,
        DomainEventFactoryInterface $domainEventFactory,
        EventStreamAggregateRootFactory $aggregateRootFactory,
        TokenStorageInterface $tokenStorage,
        AdapterInterface $cache
    ) {
        $this->eventStoreRepository = $eventStoreRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->domainEventFactory = $domainEventFactory;
        $this->aggregateRootFactory = $aggregateRootFactory;
        $this->tokenStorage = $tokenStorage;
        $this->cache = $cache;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \ReflectionException
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws EventStreamEmptyException
     */
    public function load(AbstractId $id, string $class = AbstractAggregateRoot::class): AbstractAggregateRoot
    {
        $item = $this->cache->getItem($this->createCacheKey($id));
        $sequence = !$item->isHit() ? 0 : $item->get()->getSequence();

        $events = $this->eventStoreRepository->load($id, $sequence);
        if (0 === count($events)) {
            if (0 === $sequence) {
                throw new EventStreamEmptyException(sprintf('Try to load not exiting stream for "%s"', $id->getValue()));
            }
        } else {
            $events = $this->domainEventFactory->create($id, $events);

            if (0 === $sequence) {
                $result = $this->aggregateRootFactory->create(new DomainEventStream($events), $class);
            } else {
                /** @var AbstractAggregateRoot $result */
                $result = $item->get();
                $result->initialize(new DomainEventStream($events));
            }

            $item->set($result);
            $this->cache->save($item);
        }

        return $item->get();
    }

    /**
     * {@inheritDoc}
     *
     * @throws \ReflectionException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function exists(AbstractId $id): bool
    {
        try {
            $this->load($id);
            $result = true;
        } catch (EventStreamEmptyException $exception) {
            $result = false;
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void
    {
        $events = $aggregateRoot->popEvents();

        $userId = $this->tokenStorage->getToken() ? $this->tokenStorage->getToken()->getUser()->getId()->getValue() : null;

        $this->eventStoreRepository->append($aggregateRoot->getId(), $events, $userId);
        foreach ($events as $envelope) {
            $this->eventDispatcher->dispatch($envelope);
        }

        $this->cache->deleteItem($this->createCacheKey($aggregateRoot->getId()));
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function delete(AbstractAggregateRoot $aggregateRoot): void
    {
        $this->eventStoreRepository->delete($aggregateRoot->getId());

        $this->cache->deleteItem($this->createCacheKey($aggregateRoot->getId()));
    }

    /**
     * @param AbstractId $id
     *
     * @return string
     */
    private function createCacheKey(AbstractId $id): string
    {
        return sprintf(self::CACHE_KEY, $id->getValue());
    }
}
