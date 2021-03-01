<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Persistence\Repository;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeDeletedEvent;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManager;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Webmozart\Assert\Assert;

class EventStoreAttributeRepository implements AttributeRepositoryInterface
{
    private EventStoreManager $manager;

    public function __construct(EventStoreManager $manager)
    {
        $this->manager = $manager;
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
        $this->manager->save($aggregateRoot);
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function delete(AbstractAttribute $aggregateRoot): void
    {
        $aggregateRoot->apply(new AttributeDeletedEvent($aggregateRoot->getId()));
        $this->save($aggregateRoot);

        $this->manager->delete($aggregateRoot);
    }
}
