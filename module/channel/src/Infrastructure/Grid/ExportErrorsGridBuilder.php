<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\TranslatableColumn;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Grid\Filter\DateFilter;
use Ergonode\Grid\Column\DateColumn;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Grid\Grid;
use Ergonode\Grid\Column\IdColumn;

class ExportErrorsGridBuilder implements GridBuilderInterface
{
    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $grid = new Grid();
        $grid
            ->addColumn('id', new IdColumn('id'))
            ->addColumn('created_at', new DateColumn('created_at', 'Created at', new DateFilter()))
            ->addColumn(
                'message',
                new TranslatableColumn('message', 'Message', 'parameters', 'channel')
            )
            ->orderBy('id', 'ASC');

        return $grid;
    }
}
