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
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class AttributeGrid extends AbstractGrid
{
    /**
     * @var AttributeTypeDictionaryProvider
     */
    private $attributeTypeDictionaryProvider;

    /**
     * @var AttributeGroupDictionaryProvider
     */
    private $attributeGroupDictionaryProvider;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param AttributeTypeDictionaryProvider  $attributeTypeDictionaryProvider
     * @param AttributeGroupDictionaryProvider $attributeGroupDictionaryProvider
     * @param TranslatorInterface              $translator
     */
    public function __construct(
        AttributeTypeDictionaryProvider $attributeTypeDictionaryProvider,
        AttributeGroupDictionaryProvider $attributeGroupDictionaryProvider,
        TranslatorInterface $translator
    ) {
        $this->attributeTypeDictionaryProvider = $attributeTypeDictionaryProvider;
        $this->attributeGroupDictionaryProvider = $attributeGroupDictionaryProvider;
        $this->translator = $translator;
    }

    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $types = $this->attributeTypeDictionaryProvider->getDictionary($language);
        $groups = $this->attributeGroupDictionaryProvider->getDictionary($language);

        $filters = $configuration->getFilters();

        $id = new TextColumn('id', $this->trans('Id'), new TextFilter($filters->get('id')));
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $index = new IntegerColumn('index', $this->trans('Index'), new TextFilter($filters->get('index')));
        $this->addColumn('index', $index);
        $this->addColumn('code', new TextColumn('code', $this->trans('Code'), new TextFilter($filters->get('code'))));
        $column = new TextColumn('label', $this->trans('Name'), new TextFilter($filters->get('label')));
        $this->addColumn('label', $column);
        $column = new TextColumn('type', $this->trans('Type'), new SelectFilter($types, $filters->get('type')));
        $this->addColumn('type', $column);
        $column = new BoolColumn('multilingual', $this->trans('Multilingual'));
        $this->addColumn('multilingual', $column);
        $this->addColumn('groups', new MultiSelectColumn('groups', $this->trans('Groups'), new MultiSelectFilter($groups, $filters->get('groups'))));
        $this->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'route' => 'ergonode_attribute_read',
                'parameters' => ['language' => $language->getCode(), 'attribute' => '{id}'],
            ],
            'edit' => [
                'route' => 'ergonode_attribute_change',
                'parameters' => ['language' => $language->getCode(), 'attribute' => '{id}'],
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'route' => 'ergonode_attribute_delete',
                'parameters' => ['language' => $language->getCode(), 'attribute' => '{id}'],
                'method' => Request::METHOD_DELETE,
            ],
        ]));
        $this->orderBy('index', 'DESC');
        $this->setConfiguration(self::PARAMETER_ALLOW_COLUMN_RESIZE, true);
    }

    /**
     * @param string $id
     * @param array  $parameters
     *
     * @return string
     */
    private function trans(string $id, array $parameters = []): string
    {
        return $this->translator->trans($id, $parameters, 'grid');
    }
}
