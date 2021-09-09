<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

interface ProductStatusQueryInterface
{
    /**
     * @return ProductId[]
     */
    public function findProductIdsByStatusId(StatusId $statusId): array;
}
