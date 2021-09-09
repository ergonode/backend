<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;
use Doctrine\DBAL\Query\QueryBuilder;

interface TransitionGridQueryInterface
{
    public function getDataSet(WorkflowId $workflowId, Language $language): QueryBuilder;
}
