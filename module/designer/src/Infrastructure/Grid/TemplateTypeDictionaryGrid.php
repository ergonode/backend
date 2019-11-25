<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\IntegerColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;

/**
 */
class TemplateTypeDictionaryGrid extends AbstractGrid
{
    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $filter = $configuration->getFilters();

        $this->addColumn('type', new TextColumn('type', 'Type', new TextFilter($filter->get('type'))));
        $this->addColumn('variant', new TextColumn('variant', 'Variant', new TextFilter($filter->get('variant'))));
        $this->addColumn('label', new TextColumn('label', 'Label', new TextFilter($filter->get('label'))));
        $this->addColumn('min_width', new IntegerColumn('min_width', 'Minimal width', new TextFilter($filter->get('min_width'))));
        $this->addColumn('min_height', new IntegerColumn('min_height', 'Minimal height', new TextFilter($filter->get('min_height'))));
        $this->addColumn('max_width', new IntegerColumn('max_width', 'Maximal width', new TextFilter($filter->get('max_width'))));
        $this->addColumn('max_height', new IntegerColumn('max_height', 'Maximal height', new TextFilter($filter->get('max_height'))));
    }
}
