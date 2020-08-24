<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\NumericColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\DateFilter;
use Ergonode\Grid\Filter\NumericFilter;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;

/**
 */
class ImportErrorsGrid extends AbstractGrid
{
    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $id = new TextColumn('id', 'Id');
        $id->setVisible(false);
        $this->addColumn('id', $id);

        $this->addColumn('line', new NumericColumn('line', 'Record number', new NumericFilter()));
        $this->addColumn('processed_at', new TextColumn('processed_at', 'Processed at', new DateFilter()));
        $this->addColumn('message', new TextColumn('message', 'Error description', new TextFilter()));

        $this->orderBy('line', 'ASC');
    }
}
