<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Renderer;

use Ergonode\Grid\AbstractGrid;

/**
 */
class RowRenderer
{
    /**
     * @param AbstractGrid $grid
     * @param array        $row
     *
     * @return array
     */
    public function render(AbstractGrid $grid, array $row): array
    {
        $result = [];
        foreach ($grid->getColumns() as $id => $column) {
            $result[$id] = $column->render($id, $row);
        }

        return $result;
    }
}
