<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Grid;

use Ergonode\Attribute\Domain\Provider\Dictionary\AttributeGroupDictionaryProvider;
use Ergonode\Attribute\Domain\Provider\Dictionary\AttributeTypeDictionaryProvider;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\BoolColumn;
use Ergonode\Grid\Column\IntegerColumn;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\MultiSelectColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Grid\Filter\SelectFilter;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 */
class AttributeGrid extends AbstractGrid
{
    /**
     * @var AttributeTypeDictionaryProvider
     */
    private AttributeTypeDictionaryProvider $attributeTypeDictionaryProvider;

    /**
     * @var AttributeGroupDictionaryProvider
     */
    private AttributeGroupDictionaryProvider $attributeGroupDictionaryProvider;

    /**
     * @param AttributeTypeDictionaryProvider  $attributeTypeDictionaryProvider
     * @param AttributeGroupDictionaryProvider $attributeGroupDictionaryProvider
     */
    public function __construct(
        AttributeTypeDictionaryProvider $attributeTypeDictionaryProvider,
        AttributeGroupDictionaryProvider $attributeGroupDictionaryProvider
    ) {
        $this->attributeTypeDictionaryProvider = $attributeTypeDictionaryProvider;
        $this->attributeGroupDictionaryProvider = $attributeGroupDictionaryProvider;
    }

    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $types = $this->attributeTypeDictionaryProvider->getDictionary($language);
        $groups = $this->attributeGroupDictionaryProvider->getDictionary($language);

        $id = new TextColumn('id', 'Id', new TextFilter());
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $index = new IntegerColumn('index', 'Index', new TextFilter());
        $this->addColumn('index', $index);
        $this->addColumn('code', new TextColumn('code', 'Code', new TextFilter()));
        $column = new TextColumn('label', 'Name', new TextFilter());
        $this->addColumn('label', $column);
        $column = new TextColumn('type', 'Type', new SelectFilter($types));
        $this->addColumn('type', $column);
        $column = new BoolColumn('multilingual', 'Multilingual');
        $this->addColumn('multilingual', $column);
        $this->addColumn('groups', new MultiSelectColumn('groups', 'Groups', new MultiSelectFilter($groups)));
        $this->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'privilege' => 'ATTRIBUTE_READ',
                'show' => ['system' => false],
                'route' => 'ergonode_attribute_read',
                'parameters' => ['language' => $language->getCode(), 'attribute' => '{id}'],
            ],
            'edit' => [
                'privilege' => 'ATTRIBUTE_UPDATE',
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
        $this->setConfiguration(self::PARAMETER_ALLOW_COLUMN_RESIZE, true);
    }
}
