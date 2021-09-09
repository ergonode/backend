<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Ergonode\Importer\Domain\Query\ImportGridQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use Doctrine\DBAL\Query\QueryBuilder;

class DbalImportGridQuery implements ImportGridQueryInterface
{
    private const TABLE = 'importer.import';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function gteGridQuery(SourceId $id): QueryBuilder
    {
        $query = $this->connection->createQueryBuilder();

        return $query->select('id, status, source_id, created_at, updated_at, started_at, ended_at')
            ->addSelect(
                '(SELECT count(*)
                        FROM importer.import_error ie
                        WHERE ie.import_id = i.id
                        ) AS errors'
            )
            ->addSelect(
                '(SELECT count(*)
                        FROM importer.import_line il
                        WHERE il.import_id = i.id
                        ) AS records'
            )
            ->from(self::TABLE, 'i')
            ->andWhere($query->expr()->eq('source_id', ':sourceId'))
            ->setParameter('sourceId', $id->getValue());
    }
}
