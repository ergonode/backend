<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Factory;

use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Ergonode\Grid\Filter\FilterBuilderProvider;
use Doctrine\DBAL\Connection;

class DbalDataSetFactory
{
    private FilterBuilderProvider $filterBuilderProvider;

    private Connection $connection;

    public function __construct(FilterBuilderProvider $filterBuilderProvider, Connection $connection)
    {
        $this->filterBuilderProvider = $filterBuilderProvider;
        $this->connection = $connection;
    }

    public function create(QueryBuilder $queryBuilder): DataSetInterface
    {
        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $queryBuilder->getSQL()), 't');
        $result->setParameters($queryBuilder->getParameters(), $queryBuilder->getParameterTypes());

        return new DbalDataSet($result, $this->filterBuilderProvider);
    }
}
