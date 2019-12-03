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
        $id = new TextColumn('id', 'Id');
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $code = new TextColumn('code', 'Name', new TextFilter());
        $this->addColumn('code', $code);
        $name = new TextColumn('name', 'Name', new TextFilter());
        $this->addColumn('name', $name);
        $active = new BoolColumn('active', 'active', new TextFilter());
        $this->addColumn('active', $active);
    }
}
