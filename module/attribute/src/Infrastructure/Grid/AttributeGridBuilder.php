<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Grid;

use Ergonode\Grid\Filter\Option\LabelFilterOption;
use Ergonode\Grid\Filter\Option\FilterOption;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\Column\IntegerColumn;
use Ergonode\Grid\Column\SelectColumn;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Grid\Column\MultiSelectColumn;
use Ergonode\Grid\Column\LinkColumn;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\Attribute\Domain\Provider\Dictionary\AttributeTypeDictionaryProvider;
use Ergonode\Attribute\Domain\Query\AttributeGroupQueryInterface;
use Ergonode\Grid\Grid;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\Grid\Column\IdColumn;

class AttributeGridBuilder implements GridBuilderInterface
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

    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $types = $this->getTypes($language);
        $groups = $this->getGroups($language);
        $scopes = $this->getScope();

        $grid = new Grid();
        $grid
            ->addColumn('id', new IdColumn('id'))
            ->addColumn('index', new IntegerColumn('index', 'Index', new TextFilter()))
            ->addColumn('code', new TextColumn('code', 'System name', new TextFilter()))
            ->addColumn('label', new TextColumn('label', 'Name', new TextFilter()))
            ->addColumn('type', new SelectColumn('type', 'Type', new MultiSelectFilter($types)))
            ->addColumn('scope', new SelectColumn('scope', 'Scope', new MultiSelectFilter($scopes)))
            ->addColumn('groups', new MultiSelectColumn('groups', 'Groups', new MultiSelectFilter($groups)))
            ->addColumn('_links', new LinkColumn('hal', [
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
            ]))
            ->orderBy('index', 'DESC');

        return $grid;
    }

    private function getTypes(Language $language): array
    {
        $result = [];
        foreach ($this->attributeTypeDictionaryProvider->getDictionary($language) as $key => $value) {
            $result[] = new LabelFilterOption($key, $value);
        }

        return $result;
    }

    private function getGroups(Language $language): array
    {
        $result = [];
        foreach ($this->attributeGroupQuery->getAttributeGroups($language) as $value) {
            $result[] = new FilterOption($value['id'], $value['code'], $value['label']);
        }

        return $result;
    }

    private function getScope(): array
    {
        $result = [];
        foreach (AttributeScope::AVAILABLE as $item) {
            $result[] = new LabelFilterOption($item, $item);
        }


        return $result;
    }
}
