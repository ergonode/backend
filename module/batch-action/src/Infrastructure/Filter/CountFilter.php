<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Filter;

use Doctrine\DBAL\Connection;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilterInterface;
use Ergonode\BatchAction\Infrastructure\Provider\FilteredQueryBuilderInterface;

class CountFilter
{
    private FilteredQueryBuilderInterface $filteredQueryBuilder;

    private Connection $connection;

    public function __construct(
        FilteredQueryBuilderInterface $filteredQueryBuilder,
        Connection $connection
    ) {
        $this->filteredQueryBuilder = $filteredQueryBuilder;
        $this->connection = $connection;
    }

    public function filter(BatchActionFilterInterface $filter): int
    {
        $filteredQueryBuilder = $this->filteredQueryBuilder->build($filter);

        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->select('COUNT (*)')
            ->from("({$filteredQueryBuilder->getSQL()})", 'sq')
            ->setParameters($filteredQueryBuilder->getParameters(), $filteredQueryBuilder->getParameterTypes());

        return $queryBuilder->execute()->fetch(\PDO::FETCH_COLUMN);
    }
}
