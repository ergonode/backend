<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Account\Domain\Query\PrivilegeQueryInterface;
use Ergonode\Account\Domain\ValueObject\Privilege;
use Ergonode\Account\Domain\ValueObject\PrivilegeEndPoint;

class DbalPrivilegeQuery implements PrivilegeQueryInterface
{
    public const PRIVILEGES = 'privileges';
    public const PRIVILEGES_GROUP_PRIVILEGES = 'privileges_group_privileges';
    public const PRIVILEGES_TABLE = 'privileges_group';
    public const PRIVILEGES_GROUP_TABLE = 'privileges_family';
    public const FIELDS = [
        'p.id',
        'p.code',
        'p.area',
        'g.description',
    ];

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
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

    public function getPrivilegesEndPoint(): array
    {
        $qb = $this->connection->createQueryBuilder()
            ->select(['id', 'name'])
            ->from(self::PRIVILEGES);

        return $qb->execute()->fetchAll();
    }

    /**
     * @param Privilege[] $privileges
     *
     * @return PrivilegeEndPoint[]
     */
    public function getPrivilegesEndPointByPrivilegesGroup(array $privileges): array
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select(['DISTINCT p.name'])
            ->from(self::PRIVILEGES, 'p')
            ->join('p', self::PRIVILEGES_GROUP_PRIVILEGES, 'pgp', 'p.id = pgp.privileges_id')
            ->join('pgp', self::PRIVILEGES_TABLE, 'pg', 'pg.id = pgp.privileges_group_id')
            ->Where($qb->expr()->in('pg.code', ':codes'))
            ->setParameter(':codes', $privileges, Connection::PARAM_STR_ARRAY);

        $result = $qb->execute()->fetchAll(\PDO::FETCH_COLUMN);

        foreach ($result as &$item) {
            $item = new PrivilegeEndPoint($item);
        }

        return $result;
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from(self::PRIVILEGES_TABLE, 'p')
            ->leftJoin('p', self::PRIVILEGES_GROUP_TABLE, 'g', 'g.area = p.area');
    }
}
