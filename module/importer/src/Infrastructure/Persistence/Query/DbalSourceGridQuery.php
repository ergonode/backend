<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Importer\Domain\Query\SourceGridQueryInterface;

class DbalSourceGridQuery implements SourceGridQueryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getGridQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('s.id, s.name, s.type')
            ->addSelect('(SELECT count(*) FROM importer.import WHERE source_id = s.id) AS imports')
            ->from('importer.source', 's');
    }
}
