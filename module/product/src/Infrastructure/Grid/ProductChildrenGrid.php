<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\ImageColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;

/**
 */
class ProductChildrenGrid extends AbstractGrid
{
    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $id = new TextColumn('id', 'Id', new TextFilter());
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $this->addColumn('sku', new TextColumn('code', 'Code', new TextFilter()));
        $this->addColumn('default_image', new ImageColumn('default_image', 'Default image'));
        $this->addColumn('default_label', new TextColumn('default_label', 'Default label', new TextFilter()));

//        $this->addColumn('_links', new LinkColumn('hal', [
//            'get' => [
//                'route' => 'ergonode_product_collection_read',
//                'parameters' => ['language' => $language->getCode(), 'collection' => '{id}'],
//            ],
//            'edit' => [
//                'route' => 'ergonode_product_collection_change',
//                'parameters' => ['language' => $language->getCode(), 'collection' => '{id}'],
//                'method' => Request::METHOD_PUT,
//            ],
//            'delete' => [
//                'route' => 'ergonode_product_collection_delete',
//                'parameters' => ['language' => $language->getCode(), 'collection' => '{id}'],
//                'method' => Request::METHOD_DELETE,
//            ],
//        ]));
    }
}
