<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Grid;

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

class ProductCategoryGridBuilder implements GridBuilderInterface
{
    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $grid = new Grid();
        $grid
            ->addColumn('id', new IdColumn('id'))
            ->addColumn('code', new TextColumn('code', 'Code', new TextFilter()))
            ->addColumn('name', new TextColumn('name', 'Name', new TextFilter()))
            ->addColumn(
                '_links',
                new LinkColumn(
                    'hal',
                    [
                        'delete' => [
                            'route' => 'ergonode_product_category_remove',
                            'parameters' => [
                                'language' => $language->getCode(),
                                'product' => '{product_id}',
                                'category' => '{id}',
                            ],
                            'privilege' => 'PRODUCT_DELETE_CATEGORY',
                            'method' => Request::METHOD_DELETE,
                        ],
                    ]
                )
            )
            ->orderBy('name', 'DESC');

        return $grid;
    }
}
