<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Factory\DataSet;

use Doctrine\DBAL\Connection;
use Ergonode\Grid\DbalDataSetQueryInterface;
use Ergonode\Grid\Filter\FilterBuilderProvider;
use Ergonode\Product\Infrastructure\Grid\Builder\DataSetQueryBuilderProvider;
use Ergonode\Product\Infrastructure\Persistence\DataSet\DbalQueryBuilderProductDataSet;

class DbalQueryBuilderProductDataSetFactory
{
    private Connection $connection;

    private DataSetQueryBuilderProvider $queryBuilderProvider;

    protected FilterBuilderProvider $filterBuilderProvider;

    public function __construct(
        Connection $connection,
        DataSetQueryBuilderProvider $queryBuilderProvider,
        FilterBuilderProvider $filterBuilderProvider
    ) {
        $this->connection = $connection;
        $this->queryBuilderProvider = $queryBuilderProvider;
        $this->filterBuilderProvider = $filterBuilderProvider;
    }

    public function create(): DbalDataSetQueryInterface
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        return new DbalQueryBuilderProductDataSet(
            $queryBuilder,
            $this->queryBuilderProvider,
            $this->filterBuilderProvider
        );
    }
}
