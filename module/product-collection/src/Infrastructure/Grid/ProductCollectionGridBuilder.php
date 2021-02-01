<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\DateColumn;
use Ergonode\Grid\Column\IntegerColumn;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\SelectColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\DateFilter;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Grid\Filter\Option\FilterOption;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\ProductCollection\Domain\Query\ProductCollectionTypeQueryInterface;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Grid\Grid;

class ProductCollectionGridBuilder implements GridBuilderInterface
{
    private ProductCollectionTypeQueryInterface $query;

    public function __construct(ProductCollectionTypeQueryInterface $query)
    {
        $this->query = $query;
    }

    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $grid = new Grid();
        $types = $this->getType($language);

        $id = new TextColumn('id', 'Id', new TextFilter());
        $id->setVisible(false);
        $grid->addColumn('id', $id);
        $grid->addColumn('code', new TextColumn('code', 'System name', new TextFilter()));
        $grid->addColumn('type_id', new SelectColumn('type_id', 'Type', new MultiSelectFilter($types)));
        $grid->addColumn('name', new TextColumn('name', 'Name', new TextFilter()));
        $grid->addColumn('description', new TextColumn('description', 'Description', new TextFilter()));
        $grid->addColumn('elements_count', new IntegerColumn('elements_count', 'Number of products', new TextFilter()));
        $grid->addColumn('created_at', new DateColumn('created_at', 'Created at', new DateFilter()));
        $grid->addColumn('edited_at', new DateColumn('edited_at', 'Edited at', new DateFilter()));

        $grid->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'route' => 'ergonode_product_collection_read',
                'parameters' => ['language' => $language->getCode(), 'productCollection' => '{id}'],
                'privilege' => 'PRODUCT_COLLECTION_READ',
            ],
            'edit' => [
                'route' => 'ergonode_product_collection_change',
                'parameters' => ['language' => $language->getCode(), 'productCollection' => '{id}'],
                'privilege' => 'PRODUCT_COLLECTION_UPDATE',
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'route' => 'ergonode_product_collection_delete',
                'parameters' => ['language' => $language->getCode(), 'productCollection' => '{id}'],
                'privilege' => 'PRODUCT_COLLECTION_DELETE',
                'method' => Request::METHOD_DELETE,
            ],
        ]));
        $grid->orderBy('created_at', 'DESC');

        return $grid;
    }

    private function getType(Language $language): array
    {
        $result = [];
        foreach ($this->query->getCollectionTypes($language) as $option) {
            $result[] = new FilterOption($option['id'], $option['code'], $option['label']);
        }

        return $result;
    }
}
