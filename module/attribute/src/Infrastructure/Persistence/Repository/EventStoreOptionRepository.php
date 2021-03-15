<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Persistence\Repository;

use Doctrine\DBAL\DBALException;
use Ergonode\Attribute\Domain\Entity\AbstractOption;
use Ergonode\Attribute\Domain\Event\Option\OptionRemovedEvent;
use Ergonode\Attribute\Domain\Repository\OptionRepositoryInterface;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManagerInterface;
use Ergonode\SharedKernel\Domain\AggregateId;
use Webmozart\Assert\Assert;

class EventStoreOptionRepository implements OptionRepositoryInterface
{
    private EventStoreManagerInterface $manager;

    public function __construct(EventStoreManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @throws \ReflectionException
     */
    public function load(AggregateId $id): ?AbstractOption
    {
        /** @var AbstractOption|null $aggregate */
        $aggregate = $this->manager->load($id);
        Assert::nullOrIsInstanceOf($aggregate, AbstractOption::class);

        return $aggregate;
    }

    /**
     * @throws DBALException
     */
    public function save(AbstractOption $aggregateRoot): void
    {
        $this->manager->save($aggregateRoot);
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function delete(AbstractOption $aggregateRoot): void
    {
        $aggregateRoot->apply(new OptionRemovedEvent($aggregateRoot->getId()));
        $this->save($aggregateRoot);

        $this->manager->delete($aggregateRoot);
    }
}
