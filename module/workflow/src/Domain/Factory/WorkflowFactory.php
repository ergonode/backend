<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Factory;

use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\Entity\AbstractWorkflow;
use Ergonode\Workflow\Domain\Entity\Workflow;

class WorkflowFactory
{
    /**
     * @param StatusId[] $statuses
     *
     *
     * @throws \Exception
     */
    public function create(
        WorkflowId $id,
        string $code,
        array $statuses = [],
        ?StatusId $defaultStatus = null
    ): AbstractWorkflow {
        Assert::allIsInstanceOf($statuses, StatusId::class);

        $workflow = new Workflow(
            $id,
            $code,
            $statuses
        );

        if ($defaultStatus) {
            if (!$workflow->hasStatus($defaultStatus)) {
                $workflow->addStatus($defaultStatus);
            }
            $workflow->setDefaultStatus($defaultStatus);
        }

        return $workflow;
    }
}
