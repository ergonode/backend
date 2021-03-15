<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
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
    public const PRIVILEGES_TABLE = 'privileges';
    public const PRIVILEGES_GROUP_TABLE = 'privileges_group';
    public const PRIVILEGES_ENDPOINT_TABLE = 'privileges_endpoint';
    public const PRIVILEGES_ENDPOINT_PRIVILEGES_TABLE  = 'privileges_endpoint_privileges';
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
        $qb->orderBy('code', 'ASC');

        return $qb->execute()->fetchAll();
    }

    public function getPrivilegesEndPoint(): array
    {
        $qb = $this->connection->createQueryBuilder()
            ->select(['id', 'name'])
            ->from(self::PRIVILEGES_ENDPOINT_TABLE);

        return $qb->execute()->fetchAll();
    }

    /**
     * @param Privilege[] $privileges
     *
     * @return PrivilegeEndPoint[]
     */
    public function getEndpointPrivilegesByPrivileges(array $privileges): array
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select(['DISTINCT pe.name'])
            ->from(self::PRIVILEGES_ENDPOINT_TABLE, 'pe')
            ->join('pe', self::PRIVILEGES_ENDPOINT_PRIVILEGES_TABLE, 'pep', 'pe.id = pep.privileges_endpoint_id')
            ->join('pep', self::PRIVILEGES_TABLE, 'p', 'p.id = pep.privileges_id')
            ->Where($qb->expr()->in('p.code', ':codes'))
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
