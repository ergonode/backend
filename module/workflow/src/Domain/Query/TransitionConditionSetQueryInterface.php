<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\SharedKernel\Domain\Aggregate\TransitionId;

interface TransitionConditionSetQueryInterface extends TransitionQueryInterface
{
    /**
     * @return TransitionId[]
     */
    public function findIdByConditionSetId(ConditionSetId $conditionSetId): array;
}
