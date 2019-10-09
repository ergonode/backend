<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Renderer;

use Ergonode\Grid\AbstractGrid;

/**
 */
interface RowRendererInterface
{
    /**
     * @param AbstractGrid $grid
     * @param array        $row
     *
     * @return array
     */
    public function render(AbstractGrid $grid, array $row): array;
}
