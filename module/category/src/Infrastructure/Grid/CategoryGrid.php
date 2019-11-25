<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\IntegerColumn;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 */
class CategoryGrid extends AbstractGrid
{
    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $filters = $configuration->getFilters();

        $id = new TextColumn('id', 'Id');
        $id->setVisible(false);
        $this->addColumn('id', $id);

        $index = new IntegerColumn('sequence', 'Index', new TextFilter($filters->get('sequence')));
        $this->addColumn('sequence', $index);

        $this->addColumn('code', new TextColumn('code', 'Code', new TextFilter($filters->get('code'))));

        $name = new TextColumn('name', 'Name', new TextFilter($filters->get('name')));
        $this->addColumn('name', $name);

        $this->addColumn('elements_count', new IntegerColumn('elements_count', 'Number of products', new TextFilter($filters->get('elements_count'))));

        $this->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'route' => 'ergonode_category_read',
                'parameters' => ['language' => $language->getCode(), 'category' => '{id}'],
            ],
            'edit' => [
                'route' => 'ergonode_category_change',
                'parameters' => ['language' => $language->getCode(), 'category' => '{id}'],
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'route' => 'ergonode_category_delete',
                'parameters' => ['language' => $language->getCode(), 'category' => '{id}'],
                'method' => Request::METHOD_DELETE,
            ],
        ]));

        $this->orderBy('sequence', 'DESC');
        $this->setConfiguration(self::PARAMETER_ALLOW_COLUMN_RESIZE, true);
    }
}
