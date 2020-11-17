<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Repository;

use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

interface ProductWorkflowRepositoryInterface
{
    /**
     * @return string[]
     */
    public function loadStatuses(ProductId $productId): array;
}
