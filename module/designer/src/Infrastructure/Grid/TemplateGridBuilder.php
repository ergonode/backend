<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Query\TemplateGroupQueryInterface;
use Ergonode\Grid\Column\ImageColumn;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\SelectColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Grid\Filter\Option\LabelFilterOption;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Grid\Grid;
use Ergonode\Grid\Column\IdColumn;

class TemplateGridBuilder implements GridBuilderInterface
{
    private TemplateGroupQueryInterface $query;

    public function __construct(TemplateGroupQueryInterface $query)
    {
        $this->query = $query;
    }

    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $groups = $this->getGroup();

        $grid = new Grid();

        $grid
            ->addColumn('id', new IdColumn('id'))
            ->addColumn('name', new TextColumn('name', 'Name', new TextFilter()))
            ->addColumn('image_id', new ImageColumn('image_id', 'Template image'))
            ->addColumn('group_id', new SelectColumn('group_id', 'Group', new MultiSelectFilter($groups)))
            ->addColumn('default_label_attribute', new TextColumn(
                'default_label_attribute',
                'Default label attribute',
                new TextFilter()
            ))
            ->addColumn('default_image_attribute', new TextColumn(
                'default_image_attribute',
                'Default image attribute',
                new TextFilter()
            ))
            ->addColumn('_links', new LinkColumn('hal', [
                'get' => [
                    'route' => 'ergonode_designer_template_read',
                    'parameters' => ['language' => $language->getCode(), 'template' => '{id}'],
                    'privilege' => 'DESIGNER_GET_TEMPLATE',
                ],
                'edit' => [
                    'route' => 'ergonode_designer_template_change',
                    'parameters' => ['language' => $language->getCode(), 'template' => '{id}'],
                    'privilege' => 'DESIGNER_PUT_TEMPLATE',
                    'method' => Request::METHOD_PUT,
                ],
                'delete' => [
                    'route' => 'ergonode_designer_template_delete',
                    'parameters' => ['language' => $language->getCode(), 'template' => '{id}'],
                    'privilege' => 'DESIGNER_DELETE_TEMPLATE',
                    'method' => Request::METHOD_DELETE,
                ],
            ]));

        return $grid;
    }

    private function getGroup(): array
    {
        $result = [];
        foreach ($this->query->getDictionary() as $value) {
            $result[] = new LabelFilterOption($value['id'], $value['name']);
        }

        return $result;
    }
}
