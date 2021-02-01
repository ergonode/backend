<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\NumericColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Grid\Grid;

class SegmentGridBuilder implements GridBuilderInterface
{
    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $grid = new Grid();
        $id = new TextColumn('id', 'Id', new TextFilter());
        $id->setVisible(false);
        $grid->addColumn('id', $id);
        $grid->addColumn('code', new TextColumn('name', 'System name', new TextFilter()));
        $grid->addColumn('name', new TextColumn('name', 'Name', new TextFilter()));
        $grid->addColumn('description', new TextColumn('description', 'Description', new TextFilter()));
        $grid->addColumn('status', new TextColumn('status', 'Status', new TextFilter()));
        $grid->addColumn('products_count', new NumericColumn('products_count', 'Products ', new TextFilter()));
        $grid->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'route' => 'ergonode_segment_read',
                'parameters' => ['language' => $language->getCode(), 'segment' => '{id}'],
                'privilege' => 'SEGMENT_READ',
            ],
            'edit' => [
                'route' => 'ergonode_segment_change',
                'parameters' => ['language' => $language->getCode(), 'segment' => '{id}'],
                'privilege' => 'SEGMENT_UPDATE',
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'route' => 'ergonode_segment_delete',
                'parameters' => ['language' => $language->getCode(), 'segment' => '{id}'],
                'privilege' => 'SEGMENT_DELETE',
                'method' => Request::METHOD_DELETE,
            ],
        ]));
        $grid->orderBy('id', 'DESC');

        return $grid;
    }
}
