<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
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
use Ergonode\Grid\Column\IdColumn;

class UnitGridBuilder implements GridBuilderInterface
{
    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $grid = new Grid();

        $grid
            ->addColumn('id', new IdColumn('id'))
            ->addColumn('name', new TextColumn('name', 'Unit name', new TextFilter()))
            ->addColumn('symbol', new TextColumn('symbol', 'Unit symbol', new TextFilter()))
            ->addColumn('_links', new LinkColumn('hal', [
                'get' => [
                    'route' => 'ergonode_unit_read',
                    'parameters' => ['language' => $language->getCode(), 'unit' => '{id}'],
                    'privilege' => 'CORE_GET_UNIT',
                ],
                'edit' => [
                    'route' => 'ergonode_unit_change',
                    'parameters' => ['language' => $language->getCode(), 'unit' => '{id}'],
                    'method' => Request::METHOD_PUT,
                    'privilege' => 'CORE_PUT_UNIT',
                ],
                'delete' => [
                    'route' => 'ergonode_unit_delete',
                    'parameters' => ['language' => $language->getCode(), 'unit' => '{id}'],
                    'method' => Request::METHOD_DELETE,
                    'privilege' => 'CORE_DELETE_UNIT',
                ],
            ]))
            ->orderBy('name', 'DESC');

        return $grid;
    }
}
