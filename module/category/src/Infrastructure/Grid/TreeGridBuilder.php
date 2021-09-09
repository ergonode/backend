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
use Ergonode\Grid\Column\IdColumn;

class TreeGridBuilder implements GridBuilderInterface
{
    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $grid = new Grid();
        $grid
            ->addColumn('id', new IdColumn('id'))
            ->addColumn('code', new TextColumn('code', 'System name', new TextFilter()))
            ->addColumn('name', new TextColumn('name', 'Name', new TextFilter()))
            ->addColumn('_links', new LinkColumn('', [
                'get' => [
                    'route' => 'ergonode_category_tree_read',
                    'parameters' => ['language' => $language->getCode(), 'tree' => '{id}'],
                    'privilege' => 'ERGONODE_ROLE_CATEGORY_GET_TREE',
                ],
                'edit' => [
                    'route' => 'ergonode_category_tree_change',
                    'parameters' => ['language' => $language->getCode(), 'tree' => '{id}'],
                    'privilege' => 'ERGONODE_ROLE_CATEGORY_PUT_TREE',
                    'method' => Request::METHOD_PUT,
                ],
                'delete' => [
                    'route' => 'ergonode_category_tree_delete',
                    'parameters' => ['language' => $language->getCode(), 'tree' => '{id}'],
                    'privilege' => 'ERGONODE_ROLE_CATEGORY_DELETE_TREE',
                    'method' => Request::METHOD_DELETE,
                ],
            ]))
            ->orderBy('name', 'ASC');

        return $grid;
    }
}
