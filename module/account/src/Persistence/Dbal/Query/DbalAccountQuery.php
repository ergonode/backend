<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
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

        return new DbalDataSet($query);
    }

    /**
     * @param UserId $userId
     *
     * @return array
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
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from(self::TABLE, 'a');
    }
}
