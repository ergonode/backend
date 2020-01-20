<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Account\Domain\Query\AccountQueryInterface;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;

/**
 */
class DbalAccountQuery implements AccountQueryInterface
{
    private const TABLE = 'users';
    private const FIELDS = [
        'a.id',
        'a.first_name',
        'a.last_name',
        'a.username AS email',
        'a.language',
        'a.avatar_id',
        'a.role_id',
        'a.is_active',
    ];

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
        $query = $this->getQuery();
        $query->join('a', 'roles', 'r', 'r.id = a.role_id')
            ->andWhere($query->expr()->eq('hidden', ':hidden'));

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $query->getSQL()), 't');
        $result->setParameter(':hidden', false, \PDO::PARAM_BOOL);

        return new DbalDataSet($result);
    }

    /**
     * {@inheritDoc}
     */
    public function getUser(UserId $userId): array
    {
        $qb = $this->getQuery();

        return $qb
            ->andWhere($qb->expr()->eq('a.id', ':id'))
            ->setParameter(':id', $userId->getValue())
            ->execute()
            ->fetch();
    }

    /**
     * {@inheritDoc}
     */
    public function findUserIdByRoleId(RoleId $roleId): array
    {
        $queryBuilder = $this->connection->createQueryBuilder()
            ->select('id')
            ->from(self::TABLE)
            ->where('role_id = :role')
            ->setParameter('role', $roleId->getValue());
        $result = $queryBuilder->execute()->fetchAll(\PDO::FETCH_COLUMN);

        if (false === $result) {
            $result = [];
        }

        foreach ($result as &$item) {
            $item = new UserId($item);
        }

        return $result;
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from(self::TABLE, 'a');
    }
}
