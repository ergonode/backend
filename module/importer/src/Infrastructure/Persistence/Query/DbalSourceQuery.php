<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Ergonode\Grid\Filter\FilterBuilderProvider;
use Ergonode\Importer\Domain\Query\SourceQueryInterface;

class DbalSourceQuery implements SourceQueryInterface
{
    private Connection $connection;

    private FilterBuilderProvider $filterBuilderProvider;

    public function __construct(Connection $connection, FilterBuilderProvider $filterBuilderProvider)
    {
        $this->connection = $connection;
        $this->filterBuilderProvider = $filterBuilderProvider;
    }

    public function getDataSet(): DataSetInterface
    {
        $qb = $this->getQuery();

        return new DbalDataSet($qb, $this->filterBuilderProvider);
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('s.id, s.name, s.type')
            ->addSelect('(SELECT count(*) FROM importer.import WHERE source_id = s.id) AS imports')
            ->from('importer.source', 's');
    }
}
