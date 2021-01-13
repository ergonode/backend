<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;

class ProductCollectionTypeGrid extends AbstractGrid
{
    /**
     * @throws \Exception
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $id = new TextColumn('id', 'Id', new TextFilter());
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $this->addColumn('code', new TextColumn('code', 'Code', new TextFilter()));
        $this->addColumn('name', new TextColumn('name', 'Name', new TextFilter()));
        $this->addColumn('_links', new LinkColumn('hal', [
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
        ]));
        $this->orderBy('code', 'DESC');
    }
}
