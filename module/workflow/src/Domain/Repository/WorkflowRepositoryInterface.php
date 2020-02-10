<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;

/**
 */
interface WorkflowRepositoryInterface
{
    /**
     * @param WorkflowId $id
     *
     * @return null|Workflow
     */
    public function load(WorkflowId $id): ?AbstractAggregateRoot;

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void;

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function delete(AbstractAggregateRoot $aggregateRoot): void;
}
