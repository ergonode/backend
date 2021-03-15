<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Grid;

use Ergonode\Account\Infrastructure\Grid\Column\LogColumn;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\DateTimeColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\DateTimeFilter;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Grid\Grid;
use Ergonode\Grid\Column\IdColumn;

class LogGridBuilder implements GridBuilderInterface
{
    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $grid = new Grid();
        $grid
            ->addColumn('id', new IdColumn('id'))
            ->addColumn('recorded_at', new DateTimeColumn('recorded_at', 'Recorded at', new DateTimeFilter()))
            ->addColumn('author', new TextColumn('author', 'Author', new TextFilter()))
            ->addColumn('event', new LogColumn('event', 'payload', 'Message', $language))
            ->orderBy('recorded_at', 'DESC');

        return $grid;
    }
}
