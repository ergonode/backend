<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Repository;

use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\Entity\AbstractWorkflow;

interface WorkflowRepositoryInterface
{
    public function load(WorkflowId $id): ?AbstractWorkflow;

    public function save(AbstractWorkflow $aggregateRoot): void;

    public function delete(AbstractWorkflow $aggregateRoot): void;
}
