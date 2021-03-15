<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\DateTimeColumn;
use Ergonode\Grid\Column\IntegerColumn;
use Ergonode\Grid\Column\LinkColumn;
use Ergonode\Grid\Column\SelectColumn;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\Filter\DateTimeFilter;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Grid\Filter\Option\FilterOption;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\GridConfigurationInterface;
use Ergonode\ProductCollection\Domain\Query\ProductCollectionTypeQueryInterface;
use Symfony\Component\HttpFoundation\Request;
use Ergonode\Grid\GridInterface;
use Ergonode\Grid\GridBuilderInterface;
use Ergonode\Grid\Grid;
use Ergonode\Grid\Column\IdColumn;

class ProductCollectionGridBuilder implements GridBuilderInterface
{
    private ProductCollectionTypeQueryInterface $query;

    public function __construct(ProductCollectionTypeQueryInterface $query)
    {
        $this->query = $query;
    }

    public function build(GridConfigurationInterface $configuration, Language $language): GridInterface
    {
        $types = $this->getType($language);

        $grid = new Grid();

        $grid
            ->addColumn('id', new IdColumn('id'))
            ->addColumn('code', new TextColumn('code', 'System name', new TextFilter()))
            ->addColumn('type_id', new SelectColumn('type_id', 'Type', new MultiSelectFilter($types)))
            ->addColumn('name', new TextColumn('name', 'Name', new TextFilter()))
            ->addColumn('description', new TextColumn('description', 'Description', new TextFilter()))
            ->addColumn('elements_count', new IntegerColumn('elements_count', 'Number of products', new TextFilter()))
            ->addColumn('created_at', new DateTimeColumn('created_at', 'Created at', new DateTimeFilter()))
            ->addColumn('edited_at', new DateTimeColumn('edited_at', 'Edited at', new DateTimeFilter()))
            ->addColumn('_links', new LinkColumn('hal', [
                'get' => [
                    'route' => 'ergonode_product_collection_read',
                    'parameters' => ['language' => $language->getCode(), 'productCollection' => '{id}'],
                    'privilege' => 'PRODUCT_COLLECTION_GET',
                ],
                'edit' => [
                    'route' => 'ergonode_product_collection_change',
                    'parameters' => ['language' => $language->getCode(), 'productCollection' => '{id}'],
                    'privilege' => 'PRODUCT_COLLECTION_PUT',
                    'method' => Request::METHOD_PUT,
                ],
                'delete' => [
                    'route' => 'ergonode_product_collection_delete',
                    'parameters' => ['language' => $language->getCode(), 'productCollection' => '{id}'],
                    'privilege' => 'PRODUCT_COLLECTION_DELETE',
                    'method' => Request::METHOD_DELETE,
                ],
            ]))
            ->orderBy('created_at', 'DESC');

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
