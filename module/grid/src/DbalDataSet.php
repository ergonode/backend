<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Grid\Request\FilterValueCollection;

/**
 */
class DbalDataSet extends AbstractDbalDataSet
{
    /**
     * @var QueryBuilder
     */
    protected QueryBuilder $queryBuilder;

    /**
     * @param QueryBuilder $queryBuilder
     */
    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = clone $queryBuilder;
    }

    /**
     * @param ColumnInterface[]     $columns
     * @param FilterValueCollection $values
     * @param int                   $limit
     * @param int                   $offset
     * @param string|null           $field
     * @param string                $order
     *
     * @return \Traversable
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
        if ($field) {
            $queryBuilder->orderBy($field, $order);
        }
        $result = $queryBuilder->execute()->fetchAll();

        return new ArrayCollection($result);
    }

    /**
     * @param FilterValueCollection $values
     * @param ColumnInterface[]     $columns
     *
     * @return int
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
