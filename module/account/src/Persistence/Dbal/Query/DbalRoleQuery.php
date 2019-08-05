<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Account\Domain\Entity\RoleId;
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
    private $connection;

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
        return new DbalDataSet($this->getQuery());
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
     * @return string[]
     */
    public function getDictionary(): array
    {
        $qb = $this->getQuery();

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
            ->leftJoin('r', '(SELECT role_id, COUNT(*) AS users_count FROM users GROUP BY role_id)', 'uc', 'r.id = uc.role_id');
    }
}
