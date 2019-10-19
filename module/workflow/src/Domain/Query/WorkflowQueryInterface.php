<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Query;

use Ergonode\Workflow\Domain\Entity\StatusId;
use Ergonode\Workflow\Domain\Entity\WorkflowId;

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
}
