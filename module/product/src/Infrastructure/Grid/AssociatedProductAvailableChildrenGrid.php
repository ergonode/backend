<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\BoolColumn;
use Ergonode\Grid\Column\ImageColumn;
use Ergonode\Grid\Column\SelectColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Grid\Filter\Option\LabelFilterOption;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;

/**
 */
class AssociatedProductAvailableChildrenGrid extends AbstractGrid
{
    /**
     * @var TemplateQueryInterface
     */
    private TemplateQueryInterface $templateQuery;

    /**
     * @param TemplateQueryInterface $templateQuery
     */
    public function __construct(TemplateQueryInterface $templateQuery)
    {
        $this->templateQuery = $templateQuery;
    }

    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $templates = [];
        foreach ($this->templateQuery->getDictionary($language) as $key => $value) {
            $templates[] = new LabelFilterOption($key, $value);
        }
        $id = new TextColumn('id', 'Id', new TextFilter());
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $this->addColumn('sku', new TextColumn('code', 'Code', new TextFilter()));
        $this->addColumn('template', new SelectColumn('template', 'Template', new MultiSelectFilter($templates)));
        $this->addColumn('default_label', new TextColumn('default_label', 'Default label', new TextFilter()));
        $this->addColumn('default_image', new ImageColumn('default_image', 'Default image'));
        $attached = new BoolColumn('attached', 'Attached', new TextFilter());
        $attached->setEditable(true);
        $this->addColumn('attached', $attached);
    }
}
