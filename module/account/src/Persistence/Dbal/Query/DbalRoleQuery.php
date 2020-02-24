<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Account\Domain\Query\RoleQueryInterface;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;

/**
 */
class DbalRoleQuery implements RoleQueryInterface
{
    public const TABLE = 'roles';

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
     * @return DataSetInterface
     */
    public function getDataSet(): DataSetInterface
    {
        $query = $this->getQuery();
        $query->andWhere($query->expr()->eq('hidden', ':hidden'));

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $query->getSQL()), 't');
        $result->setParameter(':hidden', false, \PDO::PARAM_BOOL);

        return new DbalDataSet($result);
    }

    /**
     * @param RoleId $id
     *
     * @return int
     */
    public function getRoleUsersCount(RoleId $id): int
    {
        $qb = $this->getQuery();
        $result = $qb->andWhere($qb->expr()->eq('id', ':id'))
            ->setParameter(':id', $id->getValue())
            ->execute()
            ->fetch();

        if ($result) {
            return (int) $result['users_count'];
        }

        return 0;
    }

    /**
     * @param RoleId $id
     *
     * @return array
     */
    public function getAllRoleUsers(RoleId $id): array
    {
        $qb = $this->connection->createQueryBuilder();
        $records = $qb->select('id')
            ->from('users', 'u')
            ->where($qb->expr()->eq('role_id', ':id'))
            ->setParameter(':id', $id->getValue())
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        $result = [];

        foreach ($records as $record) {
            $result[] = new userId($record);
        }

        return $result;
    }

    /**
     * @return string[]
     */
    public function getDictionary(): array
    {
        $qb = $this->getQuery();
        $qb->andWhere($qb->expr()->eq('hidden', ':hidden'));
        $qb->setParameter(':hidden', false, \PDO::PARAM_BOOL);

        return $qb
            ->select('id, name')
            ->execute()
            ->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * @return QueryBuilder
     */
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
