<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Column\Renderer;

use Ergonode\Grid\ColumnInterface;

interface ColumnRendererInterface
{
    /**
     * @param ColumnInterface $column
     *
     * @return bool
     */
    public function supports(ColumnInterface $column): bool;

    /**
     * @param ColumnInterface $column
     * @param string          $id
     * @param array           $row
     *
     * @return mixed
     */
    public function render(ColumnInterface $column, string $id, array $row);
}
