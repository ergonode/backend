<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Renderer;

use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Grid\GridInterface;

interface RowRendererInterface
{
    /**
     * @param array $row
     *
     * @return array
     */
    public function render(GridInterface $grid, GridConfigurationInterface $configuration, array $row): array;
}
