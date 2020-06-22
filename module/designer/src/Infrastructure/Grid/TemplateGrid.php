<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Query\TemplateGroupQueryInterface;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\ImageColumn;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\SelectColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Grid\Filter\Option\LabelFilterOption;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 */
class TemplateGrid extends AbstractGrid
{
    /**
     * @var TemplateGroupQueryInterface
     */
    private TemplateGroupQueryInterface $query;

    /**
     * @param TemplateGroupQueryInterface $query
     */
    public function __construct(TemplateGroupQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $collection = [];
        foreach ($this->query->getDictionary() as $value) {
            $collection[] = new LabelFilterOption($value['id'], $value['name']);
        }
        $id = new TextColumn('id', 'Id', new TextFilter());
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $this->addColumn('name', new TextColumn('name', 'Name', new TextFilter()));
        $this->addColumn('image_id', new ImageColumn('image_id', 'Template image'));
        $this->addColumn('group_id', new SelectColumn('group_id', 'Group', new MultiSelectFilter($collection)));
        $this->addColumn('default_label_attribute', new TextColumn(
            'default_label_attribute',
            'Default label attribute',
            new TextFilter()
        ));
        $this->addColumn('default_image_attribute', new TextColumn(
            'default_image_attribute',
            'Default image attribute',
            new TextFilter()
        ));
        $this->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'route' => 'ergonode_designer_template_read',
                'parameters' => ['language' => $language->getCode(), 'template' => '{id}'],
                'privilege' => 'TEMPLATE_DESIGNER_READ',
            ],
            'edit' => [
                'route' => 'ergonode_designer_template_change',
                'parameters' => ['language' => $language->getCode(), 'template' => '{id}'],
                'privilege' => 'TEMPLATE_DESIGNER_UPDATE',
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'route' => 'ergonode_designer_template_delete',
                'parameters' => ['language' => $language->getCode(), 'template' => '{id}'],
                'privilege' => 'TEMPLATE_DESIGNER_DELETE',
                'method' => Request::METHOD_DELETE,
            ],
        ]));
    }
}
