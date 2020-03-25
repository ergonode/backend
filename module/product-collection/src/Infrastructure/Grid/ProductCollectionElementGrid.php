<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\BoolColumn;
use Ergonode\Grid\Column\DateColumn;
use Ergonode\Grid\Column\ImageColumn;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\DateFilter;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 */
class ProductCollectionElementGrid extends AbstractGrid
{
    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     *
     * @throws \Exception
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $this->addColumn('product_collection_id', new TextColumn(
            'product_collection_id',
            'Product Collection Id',
            new TextFilter()
        ));
        $this->addColumn('product_id', new TextColumn('product_id', 'Product Id', new TextFilter()));
        $this->addColumn('default_image', new ImageColumn('default_image'));
        $this->addColumn('system_name', new TextColumn('system_name', 'System name', new TextFilter()));
        $this->addColumn('sku', new TextColumn('sku', 'Sku', new TextFilter()));
        $this->addColumn('visible', new BoolColumn('visible', 'Product visible in collection'));
        $this->addColumn('created_at', new DateColumn('created_at', 'Addition date', new DateFilter()));
        $this->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'route' => 'ergonode_product_collection_element_read',
                'parameters' => [
                    'language' => $language->getCode(),
                    'collection' => '{product_collection_id}',
                    'product' => '{product_id}',
                ],
            ],
            'edit' => [
                'route' => 'ergonode_product_collection_element_change',
                'parameters' => [
                    'language' => $language->getCode(),
                    'collection' => '{product_collection_id}',
                    'product' => '{product_id}',
                ],
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'route' => 'ergonode_product_collection_element_delete',
                'parameters' => [
                    'language' => $language->getCode(),
                    'collection' => '{product_collection_id}',
                    'product' => '{product_id}',
                ],
                'method' => Request::METHOD_DELETE,
            ],
        ]));
        $this->orderBy('created_at', 'DESC');
        $this->setConfiguration(AbstractGrid::PARAMETER_ALLOW_COLUMN_RESIZE, true);
    }
}
