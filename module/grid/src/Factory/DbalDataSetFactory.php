<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Factory;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Ergonode\Grid\Filter\FilterBuilderProvider;

class DbalDataSetFactory
{

    private FilterBuilderProvider $filterBuilderProvider;

    public function __construct(FilterBuilderProvider $filterBuilderProvider)
    {
        $this->filterBuilderProvider = $filterBuilderProvider;
    }

    public function create(QueryBuilder $queryBuilder): DataSetInterface
    {
        return new DbalDataSet($queryBuilder, $this->filterBuilderProvider);
    }
}
