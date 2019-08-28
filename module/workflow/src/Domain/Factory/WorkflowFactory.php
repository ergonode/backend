<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Factory;

use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Domain\Entity\WorkflowId;

/**
 */
class WorkflowFactory
{
    /**
     * @param WorkflowId $id
     * @param string     $code
     * @param array      $statuses
     * @param array      $transitions
     *
     * @return Workflow
     *
     * @throws \Exception
     */
    public function create(WorkflowId $id, string $code, array $statuses = [], array $transitions = []): Workflow
    {
        return new Workflow(
            $id,
            $code,
            $statuses
        );
    }
}
