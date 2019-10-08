<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Column\Renderer;

use Ergonode\Grid\Column\Exception\UnsupportedColumnException;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\ColumnInterface;

/**
 */
class LinkColumnRenderer implements ColumnRendererInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(ColumnInterface $column): bool
    {
        return $column instanceof LinkColumn;
    }

    /**
     * {@inheritDoc}
     *
     * @throws UnsupportedColumnException
     */
    public function render(ColumnInterface $column, string $id, array $row): array
    {
        if (!$this->supports($column)) {
            throw new UnsupportedColumnException($column);
        }

        $links = [];

        $mapFunction = static function ($value) {
            return sprintf('{%s}', $value);
        };

        $keys = array_map($mapFunction, array_keys($row));
        $values = array_values($row);

        foreach ($this->links as $name => $link) {
            $links[$name] = str_replace($keys, $values, $link);
        }

        return $links;
    }
}
