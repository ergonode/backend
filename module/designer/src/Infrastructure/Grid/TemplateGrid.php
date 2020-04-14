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
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\Grid\Filter\Option\FilterOption;
use Ergonode\Grid\Filter\Option\LabelFilterOption;

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

        $this->addColumn('id', new TextColumn('id', 'Id'));
        $this->addColumn('name', new TextColumn('name', 'Name', new TextFilter()));
        $this->addColumn('default_text', new TextColumn('default_text', 'Default text', new TextFilter()));
        $this->addColumn('default_image', new TextColumn('default_image', 'Default Image', new TextFilter()));
        $this->addColumn('image_id', new TextColumn('image_id', 'Icon', new TextFilter()));
        $this->addColumn('group_id', new TextColumn('group_id', 'Group', new MultiSelectFilter($collection)));
        $this->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'route' => 'ergonode_condition_conditionset_read',
                'parameters' => ['language' => $language->getCode(), 'conditionSet' => '{id}'],
            ],
            'edit' => [
                'route' => 'ergonode_condition_conditionset_change',
                'parameters' => ['language' => $language->getCode(), 'conditionSet' => '{id}'],
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'route' => 'ergonode_condition_conditionset_delete',
                'parameters' => ['language' => $language->getCode(), 'conditionSet' => '{id}'],
                'method' => Request::METHOD_DELETE,
            ],
        ]));
    }
}
