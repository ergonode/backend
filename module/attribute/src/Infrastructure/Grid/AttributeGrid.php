<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Grid;

use Ergonode\Attribute\Domain\Provider\Dictionary\AttributeTypeDictionaryProvider;
use Ergonode\Attribute\Domain\Query\AttributeGroupQueryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\IntegerColumn;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\MultiSelectColumn;
use Ergonode\Grid\Column\SelectColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Grid\Filter\Option\FilterOption;
use Ergonode\Grid\Filter\Option\LabelFilterOption;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;

class AttributeGrid extends AbstractGrid
{
    private AttributeTypeDictionaryProvider $attributeTypeDictionaryProvider;

    private AttributeGroupQueryInterface $attributeGroupQuery;

    public function __construct(
        AttributeTypeDictionaryProvider $attributeTypeDictionaryProvider,
        AttributeGroupQueryInterface $attributeGroupQuery
    ) {
        $this->attributeTypeDictionaryProvider = $attributeTypeDictionaryProvider;
        $this->attributeGroupQuery = $attributeGroupQuery;
    }

    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $types = [];
        foreach ($this->attributeTypeDictionaryProvider->getDictionary($language) as $key => $value) {
            $types[] = new LabelFilterOption($key, $value);
        }
        $groups = [];
        foreach ($this->attributeGroupQuery->getAttributeGroups($language) as $value) {
            $groups[] = new FilterOption($value['id'], $value['code'], $value['label']);
        }
        $scope = [];
        foreach (AttributeScope::AVAILABLE as $item) {
            $scope[] = new LabelFilterOption($item, $item);
        }

        $id = new TextColumn('id', 'Id', new TextFilter());
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $index = new IntegerColumn('index', 'Index', new TextFilter());
        $this->addColumn('index', $index);
        $this->addColumn('code', new TextColumn('code', 'System name', new TextFilter()));
        $column = new TextColumn('label', 'Name', new TextFilter());
        $this->addColumn('label', $column);
        $column = new SelectColumn('type', 'Type', new MultiSelectFilter($types));
        $this->addColumn('type', $column);
        $column = new SelectColumn('scope', 'Scope', new MultiSelectFilter($scope));
        $this->addColumn('scope', $column);
        $this->addColumn('groups', new MultiSelectColumn('groups', 'Groups', new MultiSelectFilter($groups)));
        $this->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'privilege' => 'ATTRIBUTE_GET',
                'show' => ['system' => false],
                'route' => 'ergonode_attribute_read',
                'parameters' => ['language' => $language->getCode(), 'attribute' => '{id}'],
            ],
            'edit' => [
                'privilege' => 'ATTRIBUTE_PUT',
                'show' => ['system' => false],
                'route' => 'ergonode_attribute_change',
                'parameters' => ['language' => $language->getCode(), 'attribute' => '{id}'],
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'privilege' => 'ATTRIBUTE_DELETE',
                'show' => ['system' => false],
                'route' => 'ergonode_attribute_delete',
                'parameters' => ['language' => $language->getCode(), 'attribute' => '{id}'],
                'method' => Request::METHOD_DELETE,
            ],
        ]));
        $this->orderBy('index', 'DESC');
    }
}
