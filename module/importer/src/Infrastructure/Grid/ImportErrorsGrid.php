<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\DateColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\DateFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Grid\Column\TranslatableColumn;

class ImportErrorsGrid extends AbstractGrid
{
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $id = new TextColumn('id', 'Id');
        $id->setVisible(false);
        $this->addColumn('id', $id);

        $this->addColumn('created_at', new DateColumn('created_at', 'Created at', new DateFilter()));
        $this->addColumn('message', new TranslatableColumn('message', 'Error description', 'parameters', 'importer'));

        $this->orderBy('id', 'ASC');
    }
}
