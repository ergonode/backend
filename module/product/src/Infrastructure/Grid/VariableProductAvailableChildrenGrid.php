<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\BoolColumn;
use Ergonode\Grid\Column\ImageColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;

/**
 */
class VariableProductAvailableChildrenGrid extends AbstractGrid
{
    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $id = new TextColumn('id', 'Id', new TextFilter());
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $this->addColumn('sku', new TextColumn('code', 'Code', new TextFilter()));
        $this->addColumn('template', new TextColumn('template', 'Template', new TextFilter()));
        $this->addColumn('default_label', new TextColumn('default_label', 'Default label', new TextFilter()));
        $this->addColumn('default_image', new ImageColumn('default_image', 'Default image'));
        $this->addColumn('attach_flag', new BoolColumn('attach_flag', 'Attach flag', new TextFilter()));
    }
}
