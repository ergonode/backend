<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Factory;

use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\Entity\AbstractWorkflow;
use Ergonode\Workflow\Domain\Entity\Workflow;

/**
 */
class WorkflowFactory
{
    /**
     * @param WorkflowId $id
     * @param string     $code
     * @param StatusId[] $statuses
     *
     * @return AbstractWorkflow
     *
     * @throws \Exception
     */
    public function create(WorkflowId $id, string $code, array $statuses = []): AbstractWorkflow
    {
        Assert::allIsInstanceOf($statuses, StatusId::class);

        return new Workflow(
            $id,
            $code,
            $statuses
        );
    }
}
