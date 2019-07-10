<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid;

/**
 */
interface DataSetInterface
{
    /**
     * @param ColumnInterface[] $columns
     * @param int               $limit
     * @param int               $offset
     * @param string|null       $field
     * @param string            $order
     *
     * @return \Traversable
     */
    public function getItems(array $columns, int $limit, int $offset, ?string $field = null, string $order = 'ASC'): \Traversable;

    /**
     * @param COlumnInterface[] $columns
     *
     * @return int
     */
    public function countItems(array $columns = []): int;
}
