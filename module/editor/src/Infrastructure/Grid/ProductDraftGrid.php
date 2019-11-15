<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\BoolColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;

/**
 */
class ProductDraftGrid extends AbstractGrid
{
    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $filters = $configuration->getFilters();

        $this->addColumn('id', new TextColumn('id', 'Id'));
        $this->addColumn('product_id', new TextColumn('product_id', 'Product Id'));
        $this->addColumn('template_id', new TextColumn('template_id', 'Template Id'));
        $this->addColumn('sku', new TextColumn('sku', 'Sku', new TextFilter($filters->get('sku'))));
        $this->addColumn('type', new TextColumn('type', 'Type'));
        $this->addColumn('applied', new BoolColumn('applied', 'Id'));
    }
}
