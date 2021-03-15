<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Provider;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilterInterface;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Grid\FilterGridConfiguration;
use Ergonode\Product\Infrastructure\Factory\DataSet\DbalQueryBuilderProductDataSetFactory;
use Ergonode\Product\Infrastructure\Grid\ProductGridBuilder;

class FilteredQueryBuilder
{
    private DbalQueryBuilderProductDataSetFactory $productQueryBuilderFactory;

    private ProductGridBuilder $gridBuilder;

    private ProductIdsProvider $productIdsProvider;

    private Connection $connection;

    private LanguageQueryInterface $languageQuery;

    public function __construct(
        DbalQueryBuilderProductDataSetFactory $productQueryBuilderFactory,
        ProductGridBuilder $gridBuilder,
        ProductIdsProvider $productIdsProvider,
        Connection $connection,
        LanguageQueryInterface $languageQuery
    ) {
        $this->productQueryBuilderFactory = $productQueryBuilderFactory;
        $this->gridBuilder = $gridBuilder;
        $this->productIdsProvider = $productIdsProvider;
        $this->connection = $connection;
        $this->languageQuery = $languageQuery;
    }


    public function build(BatchActionFilterInterface $filter): QueryBuilder
    {
        $queryBuilder = $this->getQueryBuilder();
        $filterQuery = $filter->getQuery();
        if ($filterQuery) {
            if ($filter->getIds()) {
                if ($filter->getIds()->isIncluded()) {
                    //query and include id
                    $filteredQueryBuilder = $this->getFilteredQueryBuilder($filterQuery);

                    return $filteredQueryBuilder->orWhere($filteredQueryBuilder->expr()->in('id', ':productsIds'))
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
                return $queryBuilder->where($queryBuilder->expr()->in('id', ':productsIds'))
                    ->setParameter(':productsIds', $filter->getIds()->getList(), Connection::PARAM_STR_ARRAY);
            }

            //only exclude id in all system
            return $queryBuilder->where($queryBuilder->expr()->notIn('id', ':productsIds'))
                ->setParameter(':productsIds', $filter->getIds()->getList(), Connection::PARAM_STR_ARRAY);
        }

        //no filter or empty, get all items
        return $queryBuilder;
    }

    private function getFilteredQueryBuilder(string $filter): QueryBuilder
    {
        $language = $this->languageQuery->getRootLanguage();
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
