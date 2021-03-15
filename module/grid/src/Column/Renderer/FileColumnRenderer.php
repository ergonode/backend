<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Column\Renderer;

use Ergonode\Grid\Column\Exception\UnsupportedColumnException;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\Column\FileColumn;

class FileColumnRenderer implements ColumnRendererInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(ColumnInterface $column): bool
    {
        return $column instanceof FileColumn;
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

        $value = $row[$id];

        if (is_array($value)) {
            return $value;
        }

        if ($this->isJson($value)) {
            $value  = json_decode($row[$id], true);
        }

        if (is_array($value)) {
            return $value;
        }

        return $value ? [$value] : [];
    }

    private function isJson(?string $string = null): bool
    {
        if (null === $string) {
            return false;
        }

        return (json_last_error() === JSON_ERROR_NONE);
    }
}
