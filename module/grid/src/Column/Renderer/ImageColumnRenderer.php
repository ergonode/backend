<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Column\Renderer;

use Ergonode\Grid\Column\Exception\UnsupportedColumnException;
use Ergonode\Grid\Column\ImageColumn;
use Ergonode\Grid\ColumnInterface;

/**
 */
class ImageColumnRenderer implements ColumnRendererInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(ColumnInterface $column): bool
    {
        return $column instanceof ImageColumn;
    }

    /**
     * {@inheritDoc}
     *
     * @throws UnsupportedColumnException
     */
    public function render(ColumnInterface $column, string $id, array $row): ?string
    {
        if (!$this->supports($column)) {
            throw new UnsupportedColumnException($column);
        }

        return $row[$id];
    }
}
