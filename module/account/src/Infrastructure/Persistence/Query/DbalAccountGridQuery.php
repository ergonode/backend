<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Account\Domain\Query\AccountGridQueryInterface;

class DbalAccountGridQuery implements AccountGridQueryInterface
{
    private const TABLE = 'users';
    private const FIELDS = [
        'a.id',
        'a.first_name',
        'a.last_name',
        'a.username AS email',
        'a.language',
        'a.role_id',
        'a.is_active',
        'a.language_privileges_collection',
    ];

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getGridQuery(): QueryBuilder
    {
        $query = $this->getQuery();

        return $query
            ->join('a', 'roles', 'r', 'r.id = a.role_id')
            ->andWhere($query->expr()->eq('hidden', ':qb_hidden'))
            ->setParameter(':qb_hidden', false, \PDO::PARAM_BOOL);
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from(self::TABLE, 'a');
    }
}
