<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Persistence\Repository;

use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManager;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowDeletedEvent;
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Workflow\Domain\Entity\AbstractWorkflow;

class DbalWorkflowRepository implements WorkflowRepositoryInterface
{
    private EventStoreManager $manager;

    public function __construct(EventStoreManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @throws \ReflectionException
     */
    public function load(WorkflowId $id): ?AbstractWorkflow
    {
        /** @var AbstractWorkflow|null $aggregate */
        $aggregate = $this->manager->load($id);
        Assert::nullOrIsInstanceOf($aggregate, Workflow::class);

        return $aggregate;
    }

    public function save(AbstractWorkflow $aggregateRoot): void
    {
        $this->manager->save($aggregateRoot);
    }

    /**
     * @throws \Exception
     */
    public function delete(AbstractWorkflow $aggregateRoot): void
    {
        $aggregateRoot->apply(new WorkflowDeletedEvent($aggregateRoot->getId()));
        $this->save($aggregateRoot);

        $this->manager->delete($aggregateRoot);
    }
}
