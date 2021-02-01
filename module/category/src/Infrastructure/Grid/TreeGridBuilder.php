<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\Grid\Grid;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\GridBuilderInterface;

class TreeGridBuilder implements GridBuilderInterface
{
    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $grid = new Grid();
        $id = new TextColumn('id', 'Id');
        $id->setVisible(false);
        $grid->addColumn('id', $id);
        $code = new TextColumn('code', 'System name', new TextFilter());
        $grid->addColumn('code', $code);
        $grid->orderBy('code', 'ASC');
        $name = new TextColumn('name', 'Name', new TextFilter());
        $grid->addColumn('name', $name);
        $grid->addColumn('_links', new LinkColumn('', [
            'get' => [
                'route' => 'ergonode_category_tree_read',
                'parameters' => ['language' => $language->getCode(), 'tree' => '{id}'],
                'privilege' => 'CATEGORY_TREE_READ',
            ],
            'edit' => [
                'route' => 'ergonode_category_tree_change',
                'parameters' => ['language' => $language->getCode(), 'tree' => '{id}'],
                'privilege' => 'CATEGORY_TREE_UPDATE',
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'route' => 'ergonode_category_tree_delete',
                'parameters' => ['language' => $language->getCode(), 'tree' => '{id}'],
                'privilege' => 'CATEGORY_TREE_DELETE',
                'method' => Request::METHOD_DELETE,
            ],
        ]));
        $grid->orderBy('name', 'ASC');

        return $grid;
    }
}
