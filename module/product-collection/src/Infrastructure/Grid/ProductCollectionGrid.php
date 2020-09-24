<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Infrastructure\Grid;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\AbstractGrid;
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

/**
 */
class ProductCollectionGrid extends AbstractGrid
{
    /**
     * @var ProductCollectionTypeQueryInterface
     */
    private ProductCollectionTypeQueryInterface $query;

    /**
     * @param ProductCollectionTypeQueryInterface $query
     */
    public function __construct(ProductCollectionTypeQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param GridConfigurationInterface $configuration
     * @param Language                   $language
     *
     * @throws \Exception
     */
    public function init(GridConfigurationInterface $configuration, Language $language): void
    {
        $types = [];
        foreach ($this->query->getCollectionTypes($language) as $option) {
            $types[] = new FilterOption($option['id'], $option['code'], $option['label']);
        }

        $id = new TextColumn('id', 'Id', new TextFilter());
        $id->setVisible(false);
        $this->addColumn('id', $id);
        $this->addColumn('code', new TextColumn('code', 'Code', new TextFilter()));
        $this->addColumn('type_id', new SelectColumn('type_id', 'Type', new MultiSelectFilter($types)));
        $this->addColumn('name', new TextColumn('name', 'Name', new TextFilter()));
        $this->addColumn('description', new TextColumn('description', 'Description', new TextFilter()));
        $this->addColumn('elements_count', new IntegerColumn('elements_count', 'Number of products', new TextFilter()));
        $this->addColumn('created_at', new DateColumn('created_at', 'Created at', new DateFilter()));
        $this->addColumn('edited_at', new DateColumn('edited_at', 'Edited at', new DateFilter()));

        $this->addColumn('_links', new LinkColumn('hal', [
            'get' => [
                'route' => 'ergonode_product_collection_read',
                'parameters' => ['language' => $language->getCode(), 'collection' => '{id}'],
                'privilege' => 'PRODUCT_COLLECTION_READ',
            ],
            'edit' => [
                'route' => 'ergonode_product_collection_change',
                'parameters' => ['language' => $language->getCode(), 'collection' => '{id}'],
                'privilege' => 'PRODUCT_COLLECTION_UPDATE',
                'method' => Request::METHOD_PUT,
            ],
            'delete' => [
                'route' => 'ergonode_product_collection_delete',
                'parameters' => ['language' => $language->getCode(), 'collection' => '{id}'],
                'privilege' => 'PRODUCT_COLLECTION_DELETE',
                'method' => Request::METHOD_DELETE,
            ],
        ]));
        $this->orderBy('created_at', 'DESC');
    }
}
