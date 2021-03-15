<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid;

use Ergonode\Grid\Request\FilterValueCollection;

interface DataSetInterface
{
    /**
     * @param ColumnInterface[] $columns
     */
    public function getItems(
        array $columns,
        FilterValueCollection $values,
        int $limit,
        int $offset,
        ?string $field = null,
        string $order = 'ASC'
    ): \Traversable;

    /**
     * @param ColumnInterface[] $columns
     */
    public function countItems(FilterValueCollection $values, array $columns = []): int;
}
