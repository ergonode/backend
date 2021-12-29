<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Multimedia\Domain\Query\MultimediaGridQueryInterface;

class DbalMultimediaGridQuery implements MultimediaGridQueryInterface
{
    private const TABLE = 'multimedia';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getGridQuery(): QueryBuilder
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb
            ->select('m.id, m."name", m."extension", m.mime, m.hash, m.created_at, m.updated_at')
            ->addSelect('(left(m.mime, strpos(m.mime, \'/\')-1)) AS type')
            ->addSelect('(m.size / 1024.00)::NUMERIC(10,2) AS size')
            ->addSelect('m.id AS image')
            ->from(self::TABLE, 'm');
    }
}
