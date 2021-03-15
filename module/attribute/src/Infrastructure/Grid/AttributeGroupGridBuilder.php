<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\IntegerColumn;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\Grid\Grid;
use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\Column\IdColumn;

class AttributeGroupGridBuilder implements GridBuilderInterface
{
    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $links = [
            'get' => [
                'privilege' => 'ATTRIBUTE_GET_GROUP',
                'route' => 'ergonode_attribute_group_read',
                'parameters' => ['language' => $language->getCode(), 'attributeGroup' => '{id}'],
            ],
            'edit' => [
                'privilege' => 'ATTRIBUTE_PUT_GROUP',
                'route' => 'ergonode_attribute_group_change',
                'parameters' => ['language' => $language->getCode(), 'attributeGroup' => '{id}'],
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'privilege' => 'ATTRIBUTE_DELETE_GROUP',
                'route' => 'ergonode_attribute_group_delete',
                'parameters' => ['language' => $language->getCode(), 'attributeGroup' => '{id}'],
                'method' => Request::METHOD_DELETE,
            ],
        ];

        $grid = new Grid();
        $grid
            ->addColumn('id', new IdColumn('id'))
            ->addColumn('code', new TextColumn('code', 'System name', new TextFilter()))
            ->addColumn('name', new TextColumn('name', 'Name', new TextFilter()))
            ->addColumn(
                'elements_count',
                new IntegerColumn('elements_count', 'Number of attributes', new TextFilter())
            )
            ->addColumn('_links', new LinkColumn('hal', $links));

        return $grid;
    }
}
