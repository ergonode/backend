<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Column\Renderer;

use Ergonode\Attribute\Domain\Entity\Attribute\AbstractDateAttribute;
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
     *
     * @return string|\DateTimeInterface|null
     */
    public function render(ColumnInterface $column, string $id, array $row)
    {
        if (!$this->supports($column)) {
            throw new UnsupportedColumnException($column);
        }
        if (null === $row[$id]) {
            return null;
        }
        $time = strtotime($row[$id]);

        $date = false === $time ?
            null :
            (new \DateTimeImmutable())->setTimestamp($time);

        if (!$column->getAttribute() instanceof AbstractDateAttribute) {
            return $date;
        }

        return $date->format($column->getAttribute()->getFormat()->getPhpFormat());
    }
}
