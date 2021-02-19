<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Provider;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilter;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\FilterGridConfiguration;
use Ergonode\Product\Infrastructure\Factory\DataSet\DbalQueryBuilderProductDataSetFactory;
use Ergonode\Product\Infrastructure\Grid\ProductGridBuilder;

class FilteredQueryBuilderProvider
{
    private DbalQueryBuilderProductDataSetFactory $productQueryBuilderFactory;

    private ProductGridBuilder $gridBuilder;

    private ProductIdsProvider $productIdsProvider;

    private Connection $connection;

    public function __construct(
        DbalQueryBuilderProductDataSetFactory $productQueryBuilderFactory,
        ProductGridBuilder $gridBuilder,
        ProductIdsProvider $productIdsProvider,
        Connection $connection
    ) {
        $this->productQueryBuilderFactory = $productQueryBuilderFactory;
        $this->gridBuilder = $gridBuilder;
        $this->productIdsProvider = $productIdsProvider;
        $this->connection = $connection;
    }


    public function provide(?BatchActionFilter $filter): QueryBuilder
    {
        $queryBuilder = $this->getQueryBuilder();
        if ($filter) {
            $filterQuery = $filter->getQuery();
            if ($filterQuery) {
                if ($filter->getIds()) {
                    if ($filter->getIds()->isIncluded()) {
                        //query and include id
                        $filteredQueryBuilder = $this->getFilteredQueryBuilder($filterQuery);

                        return $filteredQueryBuilder->andWhere($filteredQueryBuilder->expr()->in('id', ':productsIds'))
                            ->setParameter(':productsIds', $filter->getIds()->getList(), Connection::PARAM_STR_ARRAY);
                    }

                    //query and exclude id
                    $filteredQueryBuilder = $this->getFilteredQueryBuilder($filterQuery);

                    return $filteredQueryBuilder->andWhere($filteredQueryBuilder->expr()->notIn('id', ':productsIds'))
                        ->setParameter(':productsIds', $filter->getIds()->getList(), Connection::PARAM_STR_ARRAY);
                }

                //only query
                return $this->getFilteredQueryBuilder($filter->getQuery());
            }

            if ($filter->getIds()) {
                if ($filter->getIds()->isIncluded()) {
                    //only include ids
                    return $queryBuilder->andWhere($queryBuilder->expr()->in('id', ':productsIds'))
                        ->setParameter(':productsIds', $filter->getIds()->getList(), Connection::PARAM_STR_ARRAY);
                }

                //only exclude id in all system
                return $queryBuilder->andWhere($queryBuilder->expr()->notIn('id', ':productsIds'))
                    ->setParameter(':productsIds', $filter->getIds()->getList(), Connection::PARAM_STR_ARRAY);
            }
        }

        //no filter or empty, get all items
        return $queryBuilder;
    }

    private function getFilteredQueryBuilder(string $filter): QueryBuilder
    {
        $language = new Language('en_GB');
        $configuration = new FilterGridConfiguration($filter);

        $grid = $this->gridBuilder->build($configuration, $language);

        return $this->productIdsProvider->getProductIds(
            $grid,
            $configuration,
            $this->productQueryBuilderFactory->create()
        );
    }

    private function getQueryBuilder(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('id')
            ->from('public.product', 'p');
    }
}
