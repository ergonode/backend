<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Column\Renderer;

use Ergonode\Grid\Column\DateColumn;
use Ergonode\Grid\Column\Exception\UnsupportedColumnException;
use Ergonode\Grid\ColumnInterface;

class DateColumnRenderer implements ColumnRendererInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(ColumnInterface $column): bool
    {
        return $column instanceof DateColumn;
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
        $time = strtotime($row[$id]);

        if (false === $time) {
            return null;
        }

        $dateTime = new \DateTimeImmutable();

        return $dateTime->setTimestamp($time);
    }
}
