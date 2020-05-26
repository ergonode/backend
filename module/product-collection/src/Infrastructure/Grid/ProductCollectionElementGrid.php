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
        $this->addColumn('default_image', new ImageColumn('default_image', 'Image'));
        $this->addColumn('system_name', new TextColumn('system_name', 'System name', new TextFilter()));
        $this->addColumn('sku', new TextColumn('sku', 'Sku', new TextFilter()));
        $productId = new TextColumn('id', 'Id', new TextFilter());
        $productId->setVisible(false);
        $this->addColumn('id', $productId);
        $this->addColumn('created_at', new DateColumn('created_at', 'Date added', new DateFilter()));
        $visible = new BoolColumn('visible', 'Collection visibility');
        $visible->setEditable(true);
        $this->addColumn('visible', $visible);
        $this->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'route' => 'ergonode_product_collection_element_read',
                'parameters' => [
                    'language' => $language->getCode(),
                    'collection' => '{product_collection_id}',
                    'product' => '{id}',
                ],
            ],
            'delete' => [
                'route' => 'ergonode_product_collection_element_delete',
                'parameters' => [
                    'language' => $language->getCode(),
                    'collection' => '{product_collection_id}',
                    'product' => '{id}',
                ],
                'method' => Request::METHOD_DELETE,
            ],
        ]));
        $this->orderBy('created_at', 'DESC');
    }
}
