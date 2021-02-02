<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\BoolColumn;
use Ergonode\Grid\Column\DateColumn;
use Ergonode\Grid\Column\ImageColumn;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\DateFilter;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Grid\Grid;
use Ergonode\Grid\Column\IdColumn;

class ProductCollectionElementGridBuilder implements GridBuilderInterface
{
    /**
     * @throws \Exception
     */
    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $visible = new BoolColumn('visible', 'Collection visibility');
        $visible->setEditable(true);

        $grid = new Grid();
        $grid
            ->addColumn('default_image', new ImageColumn('default_image', 'Default image'))
            ->addColumn('default_label', new TextColumn('default_label', 'Default label', new TextFilter()))
            ->addColumn('sku', new TextColumn('sku', 'Sku', new TextFilter()))
            ->addColumn('id', new IdColumn('id'))
            ->addColumn('created_at', new DateColumn('created_at', 'Added at', new DateFilter()))
            ->addColumn('visible', $visible)
            ->addColumn('_links', new LinkColumn('hal', [
                'get' => [
                    'route' => 'ergonode_product_collection_element_read',
                    'parameters' => [
                        'language' => $language->getCode(),
                        'productCollection' => '{product_collection_id}',
                        'product' => '{id}',
                    ],
                ],
                'delete' => [
                    'route' => 'ergonode_product_collection_element_delete',
                    'parameters' => [
                        'language' => $language->getCode(),
                        'productCollection' => '{product_collection_id}',
                        'product' => '{id}',
                    ],
                    'method' => Request::METHOD_DELETE,
                ],
            ]))
            ->orderBy('created_at', 'DESC');

        return $grid;
    }
}
