<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Account\Domain\Query\PrivilegeQueryInterface;

/**
 */
class DbalPrivilegeQuery implements PrivilegeQueryInterface
{
    public const PRIVILEGES_TABLE = 'privileges';
    public const PRIVILEGES_GROUP_TABLE = 'privileges_group';
    public const FIELDS = [
        'p.id',
        'p.code',
        'p.area',
        'g.description',
    ];

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param bool $hidden
     *
     * @return array
     */
    public function getPrivileges(bool $hidden = false): array
    {
        $qb = $this->getQuery();

        if (false === $hidden) {
            $qb->andWhere($qb->expr()->eq('active', ':active'))
                ->setParameter(':active', 'true', \PDO::PARAM_BOOL);
        }

        return $qb->execute()->fetchAll();
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from(self::PRIVILEGES_TABLE, 'p')
            ->leftJoin('p', self::PRIVILEGES_GROUP_TABLE, 'g', 'g.area = p.area');
    }
}
