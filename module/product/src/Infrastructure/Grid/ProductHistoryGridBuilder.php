<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\DateColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\DateFilter;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Product\Infrastructure\Grid\Column\HistoryColumn;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Grid\Grid;
use Ergonode\Grid\Column\IdColumn;

class ProductHistoryGridBuilder implements GridBuilderInterface
{
    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $grid = new Grid();

        $grid
            ->addColumn('id', new IdColumn('id'))
            ->addColumn('author', new TextColumn('author', 'Author', new TextFilter()))
            ->addColumn('recorded_at', new DateColumn('recorded_at', 'Recorded at', new DateFilter()))
            ->addColumn('event', new HistoryColumn('event', 'payload', 'Message', $language))
            ->orderBy('recorded_at', 'DESC');

        return $grid;
    }
}
