<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Column\Renderer;

use Ergonode\Grid\ColumnInterface;

interface ColumnRendererInterface
{
    public function supports(ColumnInterface $column): bool;

    /**
     * @param array $row
     *
     * @return mixed
     */
    public function render(ColumnInterface $column, string $id, array $row);
}
