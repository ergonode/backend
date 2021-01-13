<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;

class ProductCategoryGrid extends AbstractGrid
{
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $id = new TextColumn('id', 'Id');
        $id->setVisible(false);
        $this->addColumn('id', $id);


        $this->addColumn('code', new TextColumn('code', 'Code', new TextFilter()));

        $name = new TextColumn('name', 'Name', new TextFilter());
        $this->addColumn('name', $name);

        $this->addColumn(
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
        );

        $this->orderBy('name', 'DESC');
    }
}
