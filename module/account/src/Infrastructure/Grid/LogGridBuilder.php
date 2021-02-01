<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Grid;

use Ergonode\Account\Infrastructure\Grid\Column\LogColumn;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\DateColumn;
use Ergonode\Grid\Column\IntegerColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\DateFilter;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Grid\Grid;

class LogGridBuilder implements GridBuilderInterface
{
    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $grid = new Grid();
        $id = new IntegerColumn('id', 'Id');
        $id->setVisible(false);
        $grid->addColumn('id', $id);
        $grid->addColumn('recorded_at', new DateColumn('recorded_at', 'Recorded at', new DateFilter()));
        $grid->addColumn('author', new TextColumn('author', 'Author', new TextFilter()));
        $column = new LogColumn('event', 'payload', 'Message', $language);
        $grid->addColumn('event', $column);
        $grid->orderBy('recorded_at', 'DESC');

        return $grid;
    }
}
