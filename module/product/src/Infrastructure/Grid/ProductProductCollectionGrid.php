<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
use Ergonode\Grid\Column\IntegerColumn;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 */
class ProductProductCollectionGrid extends AbstractGrid
{
    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     *
     * @throws \Exception
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $id = new TextColumn('id', 'Id', new TextFilter());
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $this->addColumn('code', new TextColumn('code', 'Code', new TextFilter()));
        $this->addColumn('name', new TextColumn('name', 'Name', new TextFilter()));
        $this->addColumn('description', new TextColumn('description', 'Description', new TextFilter()));
        $this->addColumn('type_id', new TextColumn('type_id', 'Type Id', new TextFilter()));
        $this->addColumn('elements_count', new IntegerColumn('elements_count', 'Number of elements', new TextFilter()));

        $this->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'route' => 'ergonode_product_collection_read',
                'parameters' => ['language' => $language->getCode(), 'collection' => '{id}'],
            ],
            'edit' => [
                'route' => 'ergonode_product_collection_change',
                'parameters' => ['language' => $language->getCode(), 'collection' => '{id}'],
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'route' => 'ergonode_product_collection_delete',
                'parameters' => ['language' => $language->getCode(), 'collection' => '{id}'],
                'method' => Request::METHOD_DELETE,
            ],
        ]));
        $this->orderBy('id', 'DESC');
        $this->setConfiguration(AbstractGrid::PARAMETER_ALLOW_COLUMN_RESIZE, true);
    }
}
