<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Persistence\Dbal\Repository;

use Ergonode\Designer\Domain\Entity\TemplateGroup;
use Ergonode\Designer\Domain\Repository\TemplateGroupRepositoryInterface;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateGroupId;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManager;
use Webmozart\Assert\Assert;

/**
 */
class DbalTemplateGroupRepository implements TemplateGroupRepositoryInterface
{
    /**
     * @var EventStoreManager
     */
    private EventStoreManager $manager;

    /**
     * @param EventStoreManager $manager
     */
    public function __construct(EventStoreManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param TemplateGroupId $id
     *
     * @return TemplateGroup|null
     *
     * @throws \ReflectionException
     */
    public function load(TemplateGroupId $id): ?AbstractAggregateRoot
    {
        /** @var TemplateGroup $result */
        $result = $this->manager->load($id);
        Assert::nullOrIsInstanceOf($result, TemplateGroup::class);

        return $result;
    }

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void
    {
        $this->manager->save($aggregateRoot);
    }
}
