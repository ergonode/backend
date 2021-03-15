<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Infrastructure\Grid;

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

class ProductCollectionTypeGridBuilder implements GridBuilderInterface
{
    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $grid = new Grid();
        $grid
            ->addColumn('id', new IdColumn('id'))
            ->addColumn('code', new TextColumn('code', 'Code', new TextFilter()))
            ->addColumn('name', new TextColumn('name', 'Name', new TextFilter()))
            ->addColumn('_links', new LinkColumn('hal', [
                'get' => [
                    'route' => 'ergonode_product_collection_type_read',
                    'parameters' => ['language' => $language->getCode(), 'productCollectionType' => '{id}'],
                    'privilege' => 'PRODUCT_COLLECTION_GET_TYPE',
                ],
                'edit' => [
                    'route' => 'ergonode_product_collection_type_change',
                    'parameters' => ['language' => $language->getCode(), 'productCollectionType' => '{id}'],
                    'method' => Request::METHOD_PUT,
                    'privilege' => 'PRODUCT_COLLECTION_PUT_TYPE',
                ],
                'delete' => [
                    'route' => 'ergonode_product_collection_type_delete',
                    'parameters' => ['language' => $language->getCode(), 'productCollectionType' => '{id}'],
                    'method' => Request::METHOD_DELETE,
                    'privilege' => 'PRODUCT_COLLECTION_DELETE_TYPE',
                ],
            ]))
            ->orderBy('code', 'DESC');

        return $grid;
    }
}
