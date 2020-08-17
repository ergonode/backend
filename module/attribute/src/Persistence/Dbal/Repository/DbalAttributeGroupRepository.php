<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Repository;

use Ergonode\Attribute\Domain\Entity\AttributeGroup;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\Attribute\Domain\Event\Group\AttributeGroupDeletedEvent;
use Ergonode\Attribute\Domain\Repository\AttributeGroupRepositoryInterface;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\Manager\ESManager;
use Webmozart\Assert\Assert;
use Doctrine\DBAL\DBALException;

/**
 */
class DbalAttributeGroupRepository implements AttributeGroupRepositoryInterface
{
    /**
     * @var ESManager
     */
    private ESManager $manager;

    /**
     * @param ESManager $manager
     */
    public function __construct(ESManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param AttributeGroupId $id
     *
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
     * @param AbstractAggregateRoot $aggregateRoot
     *
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
