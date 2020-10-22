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

class CategoryGrid extends AbstractGrid
{
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $id = new TextColumn('id', 'Id');
        $id->setVisible(false);
        $this->addColumn('id', $id);

        $index = new IntegerColumn('sequence', 'Index', new TextFilter());
        $this->addColumn('sequence', $index);

        $this->addColumn('code', new TextColumn('code', 'System name', new TextFilter()));

        $name = new TextColumn('name', 'Name', new TextFilter());
        $this->addColumn('name', $name);

        $this->addColumn('elements_count', new IntegerColumn('elements_count', 'Number of products', new TextFilter()));

        $this->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'route' => 'ergonode_category_read',
                'parameters' => ['language' => $language->getCode(), 'category' => '{id}'],
                'privilege' => 'CATEGORY_READ',
            ],
            'edit' => [
                'route' => 'ergonode_category_change',
                'parameters' => ['language' => $language->getCode(), 'category' => '{id}'],
                'privilege' => 'CATEGORY_UPDATE',
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'route' => 'ergonode_category_delete',
                'parameters' => ['language' => $language->getCode(), 'category' => '{id}'],
                'privilege' => 'CATEGORY_DELETE',
                'method' => Request::METHOD_DELETE,
            ],
        ]));

        $this->orderBy('sequence', 'DESC');
    }
}
