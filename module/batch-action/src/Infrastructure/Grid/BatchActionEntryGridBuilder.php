<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Grid;

use Ergonode\Grid\Column\DateTimeColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\Grid;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Grid\Column\BoolColumn;
use Ergonode\BatchAction\Infrastructure\Grid\Column\BatchActionMessageColumn;

class BatchActionEntryGridBuilder implements GridBuilderInterface
{
    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $grid = new Grid();
        $grid
            ->addColumn('name', new TextColumn('system_name', 'System name', new TextFilter()))
            ->addColumn('success', new BoolColumn('success', 'Success', new TextFilter()))
            ->addColumn('messages', new BatchActionMessageColumn('messages', 'Error messages'))
            ->addColumn('processed_at', new DateTimeColumn('processed_at', 'Processed at'))
            ->orderBy('processed_at', 'DESC');

        return $grid;
    }
}
