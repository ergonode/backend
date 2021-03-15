<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Column\Renderer;

use Ergonode\Grid\Column\BoolColumn;
use Ergonode\Grid\Column\Exception\UnsupportedColumnException;
use Ergonode\Grid\ColumnInterface;

class BoolColumnRenderer implements ColumnRendererInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(ColumnInterface $column): bool
    {
        return $column instanceof BoolColumn;
    }

    /**
     * {@inheritDoc}
     *
     * @throws UnsupportedColumnException
     */
    public function render(ColumnInterface $column, string $id, array $row)
    {
        if (!$this->supports($column)) {
            throw new UnsupportedColumnException($column);
        }

        if ($row[$id]) {
            return true;
        }

        return false;
    }
}
