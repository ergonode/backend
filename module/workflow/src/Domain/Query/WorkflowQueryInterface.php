<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;

/**
 */
interface WorkflowQueryInterface
{
    /**
     * @param StatusId $id
     *
     * @return WorkflowId[]
     */
    public function getWorkflowIdsWithDefaultStatus(StatusId $id): array;

    /**
     * @param string $code
     *
     * @return WorkflowId|null
     */
    public function findWorkflowIdByCode(string $code): ?WorkflowId;
}
