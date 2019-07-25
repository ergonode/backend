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
use Ergonode\Account\Domain\Query\ProfileQueryInterface;

/**
 */
class DbalProfileQuery implements ProfileQueryInterface
{
    private const TABLE = 'users';
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
     * @param UserId $userId
     *
     * @return array
     */
    public function getProfile(UserId $userId): array
    {
        $qb = $this->getQuery();
        $result = $qb->andWhere($qb->expr()->eq('u.id', ':userId'))
            ->setParameter('userId', $userId->getValue())
            ->execute()
            ->fetch();

        return $result;
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('u.id, u.first_name, u.last_name, u.username AS email, u.language, u.avatar_id')
            ->from(self::TABLE, 'u');
    }
}
