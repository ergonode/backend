<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Account\Domain\Query\UserQueryInterface;
use Ergonode\SharedKernel\Domain\ValueObject\Email;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;

/**
 */
class DbalUserQuery implements UserQueryInterface
{
    public const TABLE = 'users';

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
     * @return string[]
     */
    public function getDictionary(): array
    {
        $qb = $this->getQuery();

        return $qb
            ->select("id, first_name || ' ' || last_name as name")
            ->execute()
            ->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * @param Email $email
     *
     * @return UserId|null
     */
    public function findIdByEmail(Email $email): ?UserId
    {
        $qb = $this->getQuery();
        $result = $qb->select('id')
            ->where($qb->expr()->eq('username', ':email'))
            ->setParameter(':email', $email->getValue())
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        if ($result) {
            return new UserId($result);
        }

        return null;
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('u.*')
            ->from(self::TABLE, 'u');
    }
}
