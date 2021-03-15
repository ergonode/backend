<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Grid;

use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\Grid\Column\MultiSelectColumn;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Grid\Filter\Option\LabelFilterOption;

class AttributeGridBuilderDecorator implements GridBuilderInterface
{
    private GridBuilderInterface $builder;

    private TemplateQueryInterface $query;

    public function __construct(GridBuilderInterface $builder, TemplateQueryInterface $query)
    {
        $this->builder = $builder;
        $this->query = $query;
    }

    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $templates = $this->getTemplates($language);

        $grid = $this->builder->build($configuration, $language);
        $grid->addColumn(
            'templates',
            new MultiSelectColumn('templates', 'Templates', new MultiSelectFilter($templates))
        );

        return $grid;
    }

    private function getTemplates(Language $language): array
    {
        $result = [];
        foreach ($this->query->getDictionary($language) as $id => $name) {
            $result[] = new LabelFilterOption($id, $name);
        }

        return $result;
    }
}
