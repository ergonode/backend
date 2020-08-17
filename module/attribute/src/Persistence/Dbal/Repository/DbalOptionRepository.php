<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Repository;

use Ergonode\Attribute\Domain\Entity\AbstractOption;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Attribute\Domain\Repository\OptionRepositoryInterface;
use Ergonode\Attribute\Domain\Event\Option\OptionRemovedEvent;
use Ergonode\EventSourcing\Infrastructure\Manager\ESManager;
use Webmozart\Assert\Assert;
use Doctrine\DBAL\DBALException;

/**
 */
class DbalOptionRepository implements OptionRepositoryInterface
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
     * @param AggregateId $id
     *
     * @return AbstractOption|null
     *
     * @throws \ReflectionException
     */
    public function load(AggregateId $id): ?AbstractOption
    {
        $aggregate = $this->manager->load($id);
        Assert::nullOrIsInstanceOf($aggregate, AbstractOption::class);

        return $aggregate;
    }

    /**
     * @param AbstractOption $aggregateRoot
     *
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
