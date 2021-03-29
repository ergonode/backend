<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Persistence\Repository;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeDeletedEvent;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManagerInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Bus\ApplicationEventBusInterface;
use Ergonode\Attribute\Application\Event\AttributeCreatedEvent;
use Ergonode\Attribute\Application\Event\AttributeUpdatedEvent;
use Ergonode\Attribute\Application\Event\AttributeDeletedEvent as AttributeDeletedApplicationEvent;

class EventStoreAttributeRepository implements AttributeRepositoryInterface
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
     * @return AbstractAttribute
     *
     * @throws \ReflectionException
     */
    public function load(AttributeId $id): ?AbstractAttribute
    {
        /** @var AbstractAttribute|null $aggregate */
        $aggregate = $this->manager->load($id);
        Assert::nullOrIsInstanceOf($aggregate, AbstractAttribute::class);

        return $aggregate;
    }

    /**
     * {@inheritDoc}
     */
    public function save(AbstractAttribute $aggregateRoot): void
    {
        $isNew = $aggregateRoot->isNew();
        $this->manager->save($aggregateRoot);
        if ($isNew) {
            $this->eventBus->dispatch(new AttributeCreatedEvent($aggregateRoot));
        } else {
            $this->eventBus->dispatch(new AttributeUpdatedEvent($aggregateRoot));
        }
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function delete(AbstractAttribute $aggregateRoot): void
    {
        $aggregateRoot->apply(new AttributeDeletedEvent($aggregateRoot->getId()));
        $this->manager->save($aggregateRoot);

        $this->manager->delete($aggregateRoot);
        $this->eventBus->dispatch(new AttributeDeletedApplicationEvent($aggregateRoot));
    }
}
