<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Account\Domain\Query\RoleGridQueryInterface;

class DbalRoleGridQuery implements RoleGridQueryInterface
{
    public const TABLE = 'roles';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getGridQuery(): QueryBuilder
    {
        $query = $this->getQuery();
        $query->andWhere($query->expr()->eq('hidden', ':qb_hidden'));
        $query->setParameter(':qb_hidden', false, \PDO::PARAM_BOOL);

        return $query;
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('r.*')
            ->addSelect('COALESCE(uc.users_count, 0) AS users_count')
            ->from(self::TABLE, 'r')
            ->leftJoin(
                'r',
                '(SELECT role_id, COUNT(*) AS users_count FROM users GROUP BY role_id)',
                'uc',
                'r.id = uc.role_id'
            );
    }
}
