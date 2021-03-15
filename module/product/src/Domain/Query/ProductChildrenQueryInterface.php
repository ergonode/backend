<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

interface ProductChildrenQueryInterface
{
    /**
     * @return array
     */
    public function findProductIdByProductChildrenId(ProductId $id): array;
}
