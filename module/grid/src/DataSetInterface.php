<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid;

use Ergonode\Grid\Request\FilterValueCollection;

/**
 */
interface DataSetInterface
{
    /**
     * @param ColumnInterface[]     $columns
     * @param FilterValueCollection $values
     * @param int                   $limit
     * @param int                   $offset
     * @param string|null           $field
     * @param string                $order
     *
     * @return \Traversable
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
     * @param FilterValueCollection $values
     * @param ColumnInterface[]     $columns
     *
     * @return int
     */
    public function countItems(FilterValueCollection $values, array $columns = []): int;
}
