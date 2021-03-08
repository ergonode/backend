<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\DateTimeColumn;
use Ergonode\Grid\Filter\DateTimeFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Grid\Column\TranslatableColumn;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Grid\Grid;
use Ergonode\Grid\Column\IdColumn;

class ImportErrorsGridBuilder implements GridBuilderInterface
{
    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $grid = new Grid();
        $grid
            ->addColumn('id', new IdColumn('id'))
            ->addColumn('created_at', new DateTimeColumn('created_at', 'Created at', new DateTimeFilter()))
            ->addColumn(
                'message',
                new TranslatableColumn('message', 'Error description', 'parameters', 'importer')
            )
            ->orderBy('id', 'ASC');

        return $grid;
    }
}
