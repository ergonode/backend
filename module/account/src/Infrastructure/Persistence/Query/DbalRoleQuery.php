<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Account\Domain\Query\RoleQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;

class DbalRoleQuery implements RoleQueryInterface
{
    public const TABLE = 'roles';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

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
            ->from(self::TABLE)
            ->where('hidden = false')
        ;

        if ($search) {
            $query->andWhere('name ILIKE :search');
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
