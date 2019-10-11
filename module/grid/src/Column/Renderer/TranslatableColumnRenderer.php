<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Column\Renderer;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Grid\Column\Exception\UnsupportedColumnException;
use Ergonode\Grid\Column\TranslatableColumn;
use Ergonode\Grid\ColumnInterface;

/**
 */
class TranslatableColumnRenderer implements ColumnRendererInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(ColumnInterface $column): bool
    {
        return $column instanceof TranslatableColumn;
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

        $string = new TranslatableString(\json_decode($row[$id] ?? '[]', true));

        return $string->get($column->getLanguage());
    }
}
