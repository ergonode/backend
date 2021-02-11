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
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\FilterGridConfiguration;
use Ergonode\Grid\DataSet\DataSetGridId;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Infrastructure\Factory\DataSet\DbalProductDataSetQueryBuilderFactory;
use Ergonode\Product\Infrastructure\Grid\ProductGridBuilder;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\AggregateId;

class ProductBatchActionFilter implements BatchActionFilterIdsInterface
{
    private const TYPES = [
        'product_delete',
        'product_edit',
    ];

    private ProductQueryInterface $productQuery;

    private DbalProductDataSetQueryBuilderFactory $dataSetFactory;

    private ProductGridBuilder $gridBuilder;

    private DataSetGridId $dataSetGridId;

    /**
     * @var string []
     */
    private array $types;

    /**
     * @param string[]|null $types
     */
    public function __construct(
        ProductQueryInterface $productQuery,
        DbalProductDataSetQueryBuilderFactory $dataSetFactory,
        ProductGridBuilder $gridBuilder,
        DataSetGridId $dataSetGridId,
        ?array $types = []
    ) {
        $this->productQuery = $productQuery;
        $this->dataSetFactory = $dataSetFactory;
        $this->gridBuilder = $gridBuilder;
        $this->dataSetGridId = $dataSetGridId;
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
        $language = new Language('en_GB');
        $configuration = new FilterGridConfiguration($filter);

        $grid = $this->gridBuilder->build($configuration, $language);

        $data = $this->dataSetGridId->getItems(
            $grid,
            $configuration,
            $this->dataSetFactory->create()
        );

        $list = [];
        foreach ($data as $row) {
            $list[] = new ProductId($row);
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
