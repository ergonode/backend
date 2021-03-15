<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Column\Renderer;

use Ergonode\Grid\Column\DateTimeColumn;
use Ergonode\Grid\Column\Exception\UnsupportedColumnException;
use Ergonode\Grid\ColumnInterface;

class DateTimeColumnRenderer implements ColumnRendererInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(ColumnInterface $column): bool
    {
        return $column instanceof DateTimeColumn;
    }

    /**
     * {@inheritDoc}
     *
     * @throws UnsupportedColumnException
     */
    public function render(ColumnInterface $column, string $id, array $row): ?\DateTimeInterface
    {
        if (!$this->supports($column)) {
            throw new UnsupportedColumnException($column);
        }
        if (null === $row[$id]) {
            return null;
        }
        $time = strtotime($row[$id]);

        return false === $time ?
            null :
            (new \DateTimeImmutable())->setTimestamp($time);
    }
}
