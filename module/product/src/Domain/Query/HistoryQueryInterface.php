<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Query;

use Ergonode\Grid\DataSetInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

/**
 */
interface HistoryQueryInterface
{
    /**
     * @param ProductId $id
     *
     * @return DataSetInterface
     */
    public function getDataSet(ProductId $id): DataSetInterface;
}
