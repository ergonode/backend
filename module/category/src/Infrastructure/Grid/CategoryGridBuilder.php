<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\IntegerColumn;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Grid\Grid;
use Ergonode\Grid\Column\IdColumn;

class CategoryGridBuilder implements GridBuilderInterface
{
    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $grid = new Grid();
        $grid
            ->addColumn('id', new IdColumn('id'))
            ->addColumn('sequence', new IntegerColumn('sequence', 'Index', new TextFilter()))
            ->addColumn('code', new TextColumn('code', 'System name', new TextFilter()))
            ->addColumn('name', new TextColumn('name', 'Name', new TextFilter()))
            ->addColumn(
                'elements_count',
                new IntegerColumn('elements_count', 'Number of products', new TextFilter())
            )
            ->addColumn('_links', new LinkColumn('hal', [
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
            ]))
            ->orderBy('sequence', 'DESC');

        return $grid;
    }
}
