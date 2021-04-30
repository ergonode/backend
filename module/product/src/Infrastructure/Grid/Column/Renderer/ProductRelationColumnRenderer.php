<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Grid\Column\Renderer;

use Ergonode\Grid\Column\Exception\UnsupportedColumnException;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\Column\Renderer\ColumnRendererInterface;
use Ergonode\Product\Infrastructure\Grid\Column\ProductRelationColumn;

class ProductRelationColumnRenderer implements ColumnRendererInterface
{
    public function supports(ColumnInterface $column): bool
    {
        return $column instanceof ProductRelationColumn;
    }

    /**
     * @return mixed
     */
    public function render(ColumnInterface $column, string $id, array $row)
    {
        if (!$this->supports($column)) {
            throw new UnsupportedColumnException($column);
        }

        $value = $row[$id];

        if (null === $value) {
            return null;
        }

        if (is_array($value)) {
            return $value;
        }

        if ($this->isJson($value)) {
            $value = json_decode($row[$id], true);
        }

        if (is_array($value)) {
            return $value;
        }

        return $value ? [$value] : [];
    }

    private function isJson(string $string): bool
    {
        json_decode($string);

        return json_last_error() === JSON_ERROR_NONE;
    }
}
