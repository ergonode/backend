<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\BoolColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Grid\Column\TranslatableColumn;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Grid\Grid;

class LanguageGridBuilder implements GridBuilderInterface
{
    /**
     * {@inheritDoc}
     */
    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $grid = new Grid();
        $id = new TextColumn('id', 'Id');
        $id->setVisible(false);
        $grid->addColumn('id', $id);
        $code = new TextColumn('code', 'Name', new TextFilter());
        $grid->addColumn('code', $code);
        $name = new TranslatableColumn('name', 'Name', null, 'language');
        $grid->addColumn('name', $name);
        $active = new BoolColumn('active', 'active', new TextFilter());
        $grid->addColumn('active', $active);

        return $grid;
    }
}
