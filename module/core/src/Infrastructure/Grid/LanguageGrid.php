<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\BoolColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;

/**
 */
class LanguageGrid extends AbstractGrid
{
    /**
     * {@inheritDoc}
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $filters = $configuration->getFilters();

        $id = new TextColumn('id', 'Id');
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $code = new TextColumn('code', 'Name', new TextFilter($filters->get('code')));
        $this->addColumn('code', $code);
        $name = new TextColumn('name', 'Name', new TextFilter($filters->get('name')));
        $this->addColumn('name', $name);
        $active = new BoolColumn('active', 'active', new TextFilter($filters->get('active')));
        $this->addColumn('active', $active);
    }
}
