<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
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
        $queryBuilder = clone $this->queryBuilder;
        $this->buildFilters($queryBuilder, $values, $columns);
        $queryBuilder->setMaxResults($limit);
        $queryBuilder->setFirstResult($offset);
        if ($field && isset($columns[$field])) {
            $queryBuilder->orderBy($field, $order);
            if (isset($columns['id'])) {
                $queryBuilder->addOrderBy('id', $order);
            } // Additional 'order By' added to avoid inconsistency with sorting equal values
        }
        $result = $queryBuilder->execute()->fetchAll();

        return new ArrayCollection($result);
    }

    /**
     * @param ColumnInterface[] $columns
     */
    public function countItems(FilterValueCollection $values, array $columns = []): int
    {
        $cloneQuery = clone $this->queryBuilder;
        $this->buildFilters($cloneQuery, $values, $columns);
        $count = $cloneQuery->select('count(*) AS COUNT')
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        if ($count) {
            return $count;
        }

        return 0;
    }
}
