<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Persistence\Repository;

use Ergonode\Designer\Domain\Entity\TemplateGroup;
use Ergonode\Designer\Domain\Repository\TemplateGroupRepositoryInterface;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManagerInterface;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateGroupId;
use Webmozart\Assert\Assert;

class EventStoreTemplateGroupRepository implements TemplateGroupRepositoryInterface
{
    private EventStoreManagerInterface $manager;

    public function __construct(EventStoreManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
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

    public function save(AbstractAggregateRoot $aggregateRoot): void
    {
        $this->manager->save($aggregateRoot);
    }
}
