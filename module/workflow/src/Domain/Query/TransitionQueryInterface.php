<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Workflow\Domain\Entity\StatusId;
use Ergonode\Workflow\Domain\Entity\WorkflowId;

/**
 */
interface TransitionQueryInterface
{
    /**
     * @param WorkflowId $workflowId
     * @param Language   $language
     *
     * @return DataSetInterface
     */
    public function getDataSet(WorkflowId $workflowId, Language $language): DataSetInterface;

    /**
     * @param WorkflowId $workflowId
     * @param StatusId   $statusId
     *
     * @return mixed
     */
    public function hasStatus(WorkflowId $workflowId, StatusId $statusId): bool;
}
