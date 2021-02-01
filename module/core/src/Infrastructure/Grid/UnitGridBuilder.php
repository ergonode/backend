<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Grid\Grid;

class UnitGridBuilder implements GridBuilderInterface
{
    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $grid = new Grid();
        $id = new TextColumn('id', 'Id', new TextFilter());
        $id->setVisible(false);
        $grid->addColumn('id', $id);
        $grid->addColumn('name', new TextColumn('name', 'Unit name', new TextFilter()));
        $grid->addColumn('symbol', new TextColumn('symbol', 'Unit symbol', new TextFilter()));

        $grid->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'route' => 'ergonode_unit_read',
                'parameters' => ['language' => $language->getCode(), 'unit' => '{id}'],
            ],
            'edit' => [
                'route' => 'ergonode_unit_change',
                'parameters' => ['language' => $language->getCode(), 'unit' => '{id}'],
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'route' => 'ergonode_unit_delete',
                'parameters' => ['language' => $language->getCode(), 'unit' => '{id}'],
                'method' => Request::METHOD_DELETE,
            ],
        ]));
        $grid->orderBy('name', 'DESC');

        return $grid;
    }
}
