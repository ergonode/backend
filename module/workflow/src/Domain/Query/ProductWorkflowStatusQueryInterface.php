<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

interface ProductWorkflowStatusQueryInterface
{
    /**
     * @return string[]
     */
    public function getStatuses(ProductId $productId): array;
}
