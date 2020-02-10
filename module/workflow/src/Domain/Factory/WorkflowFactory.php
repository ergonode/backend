<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Factory;

use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use Webmozart\Assert\Assert;

/**
 */
class WorkflowFactory
{
    /**
     * @param WorkflowId   $id
     * @param string       $code
     * @param StatusCode[] $statuses
     *
     * @return Workflow
     *
     * @throws \Exception
     */
    public function create(WorkflowId $id, string $code, array $statuses = []): Workflow
    {
        Assert::allIsInstanceOf($statuses, StatusCode::class);

        $workflow = new Workflow(
            $id,
            $code,
            $statuses
        );

        return $workflow;
    }
}
