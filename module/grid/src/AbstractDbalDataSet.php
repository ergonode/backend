<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Grid\Filter\FilterBuilderProvider;
use Ergonode\Grid\Request\FilterValue;
use Ergonode\Grid\Request\FilterValueCollection;
use Ramsey\Uuid\Uuid;

abstract class AbstractDbalDataSet implements DataSetInterface
{
    public const NAMESPACE = 'b2e8fb6d-e1ac-4322-bd54-6e78926ba365';

    protected FilterBuilderProvider $filterBuilderProvider;

    public function __construct(FilterBuilderProvider $filterBuilderProvider)
    {
        $this->filterBuilderProvider = $filterBuilderProvider;
    }

    /**
     * @param ColumnInterface[] $columns
     */
    protected function buildFilters(QueryBuilder $query, FilterValueCollection $values, array $columns = []): void
    {
        /** @var FilterValue[] $filters */
        foreach ($values as $field => $filters) {
            if (array_key_exists($field, $columns)) {
                $columnFilter = $columns[$field]->getFilter();
                if ($columnFilter) {
                    if ($columns[$field]->getAttribute()) {
                        $field = Uuid::uuid5(self::NAMESPACE, $field)->toString();
                    }
                    foreach ($filters as $filter) {
                        $filterBuilder = $this->filterBuilderProvider->provide($columnFilter);
                        $filterBuilder->build($query, $field, $filter);
                    }
                }
            }
        }
    }
}
