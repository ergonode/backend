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
use Ergonode\Importer\Domain\Query\SourceQueryInterface;

class DbalSourceQuery implements SourceQueryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getDataSet(): DataSetInterface
    {
        $qb = $this->getQuery();

        return new DbalDataSet($qb);
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('s.id, s.name, s.type')
            ->addSelect('(SELECT count(*) FROM importer.import WHERE source_id = s.id) AS imports')
            ->from('importer.source', 's');
    }
}
