<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Account\Domain\Query\RoleQueryInterface;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;

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
            $result[] = new UserId($record);
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
     * @param string|null $search
     * @param int|null    $limit
     * @param string|null $field
     * @param string|null $order
     *
     * @return array
     */
    public function autocomplete(
        string $search = null,
        int $limit = null,
        string $field = null,
        ?string $order = 'ASC'
    ): array {
        $query = $this->connection->createQueryBuilder()
            ->select('id, name as label')
            ->from(self::TABLE);

        if ($search) {
            $query->orWhere('name ILIKE :search');
            $query->setParameter(':search', '%'.$search.'%');
        }

        if ($field) {
            $query->orderBy($field, $order);
        }

        if ($limit) {
            $query->setMaxResults($limit);
        }

        return $query
            ->execute()
            ->fetchAll();
    }

    /**
     * @param string $name
     *
     * @return RoleId
     */
    public function findIdByRoleName(string $name): ?RoleId
    {
        $qb = $this->connection->createQueryBuilder();
        $result = $qb->select('id')
            ->from(self::TABLE)
            ->where($qb->expr()->eq('name', ':name'))
            ->setParameter(':name', $name)
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        if ($result) {
            return new RoleId($result);
        }

        return null;
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
