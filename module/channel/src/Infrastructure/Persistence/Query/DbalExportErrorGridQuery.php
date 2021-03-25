<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Channel\Domain\Query\ExportErrorGridQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

class DbalExportErrorGridQuery implements ExportErrorGridQueryInterface
{
    private const TABLE_ERROR = 'exporter.export_error';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getGridQuery(ExportId $exportId, Language $language): QueryBuilder
    {
        $query = $this->connection->createQueryBuilder();

        return $query
            ->select('id, created_at, message, parameters')
            ->from(self::TABLE_ERROR)
            ->where($query->expr()->eq('export_id', ':exportId'))
            ->setParameter(':exportId', $exportId->getValue());
    }
}
