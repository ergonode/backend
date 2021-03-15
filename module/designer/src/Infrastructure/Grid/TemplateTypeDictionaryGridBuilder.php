<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\IntegerColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Grid\Grid;

class TemplateTypeDictionaryGridBuilder implements GridBuilderInterface
{
    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $grid = new Grid();
        $grid
            ->addColumn('type', new TextColumn('type', 'Type', new TextFilter()))
            ->addColumn('variant', new TextColumn('variant', 'Variant', new TextFilter()))
            ->addColumn('label', new TextColumn('label', 'Label', new TextFilter()))
            ->addColumn('min_width', new IntegerColumn('min_width', 'Minimal width', new TextFilter()))
            ->addColumn('min_height', new IntegerColumn('min_height', 'Minimal height', new TextFilter()))
            ->addColumn('max_width', new IntegerColumn('max_width', 'Maximal width', new TextFilter()))
            ->addColumn('max_height', new IntegerColumn('max_height', 'Maximal height', new TextFilter()));

        return $grid;
    }
}
