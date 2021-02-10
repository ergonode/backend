<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Filter\BatchAction;

use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilter;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use Ergonode\BatchAction\Infrastructure\Provider\BatchActionFilterIdsInterface;
use Ergonode\Grid\Column\IdColumn;
use Ergonode\Grid\FilterGridConfiguration;
use Ergonode\Grid\Renderer\GridRenderer;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Infrastructure\Factory\DataSet\DbalProductDataSetFactory;
use Ergonode\Product\Infrastructure\Grid\ProductGrid;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\AggregateId;

class ProductBatchActionFilter implements BatchActionFilterIdsInterface
{
    private const TYPES = [
        'product_delete',
        'product_edit',
    ];

    private ProductQueryInterface $productQuery;

    private DbalProductDataSetFactory $dataSetFactory;

    private ProductGrid $productGrid;

    private GridRenderer $gridRenderer;

    /**
     * @var string []
     */
    private array $types;

    /**
     * @param string[]|null $types
     */
    public function __construct(
        ProductQueryInterface $productQuery,
        DbalProductDataSetFactory $dataSetFactory,
        ProductGrid $productGrid,
        GridRenderer $gridRenderer,
        ?array $types = []
    ) {
        $this->productQuery = $productQuery;
        $this->dataSetFactory = $dataSetFactory;
        $this->productGrid = $productGrid;
        $this->gridRenderer = $gridRenderer;
        $this->types = $types ?: self::TYPES;
    }

    public function supports(BatchActionType $type): bool
    {
        return in_array($type->getValue(), $this->types, true);
    }

    public function filter(?BatchActionFilter $filter): array
    {
        if ($filter) {
            if ($filter->getQuery()) {
                if ($filter->getIds()) {
                    if ($filter->getIds()->isIncluded()) {
                        //query and include id
                        return array_merge($this->getByQuery($filter->getQuery()), $filter->getIds()->getList());
                    }

                    //query and exclude id
                    return array_diff($this->getByQuery($filter->getQuery()), $filter->getIds()->getList());
                }

                //only query
                return $this->getByQuery($filter->getQuery());
            }

            if ($filter->getIds()) {
                if ($filter->getIds()->isIncluded()) {
                    //only include ids
                    return $filter->getIds()->getList();
                }

                //only exclude id in all system
                return $this->getExclude($filter->getIds()->getList());
            }
        }

        //no filter or empty, get all items
        return $this->getAll();
    }

    /**
     * @return ProductId[]
     */
    private function getAll(): array
    {
        return $this->convertToProductIds($this->productQuery->getAllIds());
    }

    /**
     * @param AggregateId[] $ids
     *
     * @return ProductId[]
     */
    private function getExclude(array $ids): array
    {
        $list = [];
        foreach ($ids as $id) {
            $list[] = new ProductId($id->getValue());
        }

        return $this->convertToProductIds($this->productQuery->getOthersIds($list));
    }

    private function getByQuery(string $filter): array
    {
        $configuration = new FilterGridConfiguration($filter);

        $this->productGrid->addColumn('id', new IdColumn('id'));

        $data = $this->gridRenderer->render(
            $this->productGrid,
            $configuration,
            $this->dataSetFactory->create()
        );

        $list = [];
        foreach ($data['collection'] as $row) {
            $list[] = new ProductId($row['id']);
        }

        return $list;
    }

    /**
     * @param string[] $ids
     *
     * @return ProductId[]
     */
    private function convertToProductIds(array $ids): array
    {
        $result = [];
        foreach ($ids as $id) {
            $result[] = new ProductId($id);
        }

        return $result;
    }
}
