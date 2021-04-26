<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\ImageColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Grid\Grid;
use Ergonode\Grid\Column\IdColumn;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Grid\Filter\Option\LabelFilterOption;
use Ergonode\Grid\Column\BoolColumn;
use Ergonode\Grid\Column\SelectColumn;

class ProductRelationGridBuilder implements GridBuilderInterface
{
    private TemplateQueryInterface $templateQuery;

    public function __construct(TemplateQueryInterface $templateQuery)
    {
        $this->templateQuery = $templateQuery;
    }

    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $templates = $this->getTemplates($language);

        $grid = new Grid();

        $attached = new BoolColumn('attached', 'Attached', new TextFilter());
        $attached->setEditable(true);

        return $grid
            ->addColumn('id', new IdColumn('id'))
            ->addColumn('sku', new TextColumn('sku', 'Sku', new TextFilter()))
            ->addColumn('template_id', new SelectColumn('template_id', 'Template', new MultiSelectFilter($templates)))
            ->addColumn('default_label', new TextColumn('default_label', 'Default label', new TextFilter()))
            ->addColumn('default_image', new ImageColumn('default_image', 'Default image'))
            ->addColumn('attached', $attached);
    }

    private function getTemplates(Language $language): array
    {
        $result = [];
        foreach ($this->templateQuery->getDictionary($language) as $key => $value) {
            $result[] = new LabelFilterOption($key, $value);
        }

        return $result;
    }
}
