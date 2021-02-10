<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Grid\Filter\FilterBuilderProvider;
use Ergonode\Grid\Request\FilterValueCollection;

class DbalDataSet extends AbstractDbalDataSet
{
    protected QueryBuilder $queryBuilder;

    public function __construct(
        QueryBuilder $queryBuilderProvider,
        FilterBuilderProvider $filterBuilderQueryBuilderProvider
    ) {
        $this->queryBuilder = clone $queryBuilderProvider;
        parent::__construct($filterBuilderQueryBuilderProvider);
    }

    /**
     * @param ColumnInterface[] $columns
     */
    public function getItems(
        array $columns,
        FilterValueCollection $values,
        int $limit,
        int $offset,
        ?string $field = null,
        string $order = 'ASC'
    ): \Traversable {
        $queryBuilder = $this->getQueryBuilder($values, $columns);
        $queryBuilder->setMaxResults($limit);
        $queryBuilder->setFirstResult($offset);
        if ($field && isset($columns[$field])) {
            $queryBuilder->orderBy($field, $order);
        }
        $result = $queryBuilder->execute()->fetchAll();

        return new ArrayCollection($result);
    }

    /**
     * @param ColumnInterface[] $columns
     */
    public function countItems(FilterValueCollection $values, array $columns = []): int
    {
        $queryBuilder = $this->getQueryBuilder($values, $columns);

        $count = $queryBuilder->select('count(*) AS COUNT')
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        if ($count) {
            return $count;
        }

        return 0;
    }

    /**
     * @param ColumnInterface[] $columns
     */
    public function getQueryBuilder(FilterValueCollection $values, array $columns = []): QueryBuilder
    {
        $queryBuilder = clone $this->queryBuilder;
        $this->buildFilters($queryBuilder, $values, $columns);

        return $queryBuilder;
    }
}
