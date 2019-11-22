<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\IntegerColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;

/**
 */
class AttributeGroupGrid extends AbstractGrid
{
    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $filters = $configuration->getFilters();

        $this->addColumn('id', new TextColumn('id', 'Id', new TextFilter($filters->get('id'))));
        $this->addColumn('code', new TextColumn('code', 'Code', new TextFilter($filters->get('code'))));
        $this->addColumn('name', new TextColumn('name', 'Name', new TextFilter($filters->get('name'))));
        $this->addColumn('elements_count', new IntegerColumn('elements_count', 'Elements Count', new TextFilter($filters->get('elements_count'))));
    }
}
