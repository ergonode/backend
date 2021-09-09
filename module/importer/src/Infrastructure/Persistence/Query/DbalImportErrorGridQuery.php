<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Importer\Domain\Query\ImportErrorGridQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;

class DbalImportErrorGridQuery implements ImportErrorGridQueryInterface
{
    private const TABLE_ERROR = 'importer.import_error';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getGridQuery(ImportId $id, Language $language): QueryBuilder
    {
        $query = $this->connection->createQueryBuilder();

        return $query
            ->select('il.import_id AS id, il.created_at, il.message, il.parameters')
            ->from(self::TABLE_ERROR, 'il')
            ->where($query->expr()->eq('il.import_id', ':importId'))
            ->andWhere($query->expr()->isNotNull('il.message'))
            ->setParameter(':importId', $id->getValue());
    }
}
