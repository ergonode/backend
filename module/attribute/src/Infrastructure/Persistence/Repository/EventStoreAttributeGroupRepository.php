<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Persistence\Repository;

use Doctrine\DBAL\DBALException;
use Ergonode\Attribute\Domain\Entity\AttributeGroup;
use Ergonode\Attribute\Domain\Event\Group\AttributeGroupDeletedEvent;
use Ergonode\Attribute\Domain\Repository\AttributeGroupRepositoryInterface;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManagerInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Webmozart\Assert\Assert;

class EventStoreAttributeGroupRepository implements AttributeGroupRepositoryInterface
{
    private EventStoreManagerInterface $manager;

    public function __construct(EventStoreManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return AbstractAggregateRoot|AttributeGroup
     *
     * @throws \ReflectionException
     */
    public function load(AttributeGroupId $id): ?AbstractAggregateRoot
    {
        $aggregate = $this->manager->load($id);
        Assert::nullOrIsInstanceOf($aggregate, AttributeGroup::class);

        return $aggregate;
    }

    /**
     * @throws DBALException
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void
    {
        $this->manager->save($aggregateRoot);
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function delete(AbstractAggregateRoot $aggregateRoot): void
    {
        $aggregateRoot->apply(new AttributeGroupDeletedEvent($aggregateRoot->getId()));
        $this->save($aggregateRoot);

        $this->manager->delete($aggregateRoot);
    }
}
