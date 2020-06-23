<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Multimedia\Domain\Query\AvatarQueryInterface;
use Ergonode\Multimedia\Domain\ValueObject\Hash;
use Ergonode\SharedKernel\Domain\Aggregate\AvatarId;

/**
 */
class DbalAvatarQuery implements AvatarQueryInterface
{
    private const TABLE = 'avatar';

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
     * @param Hash $hash
     *
     * @return bool
     */
    public function fileExists(Hash $hash): bool
    {
        $query = $this->getQuery();
        $result = $query
            ->select('id')
            ->where($query->expr()->eq('hash', ':hash'))
            ->setParameter(':hash', $hash)
            ->execute()
            ->fetch();

        return $result ? true : false;
    }

    /**
     * @param Hash $hash
     *
     * @return AvatarId|null
     */
    public function findIdByHash(Hash $hash): ?AvatarId
    {
        $query = $this->getQuery();
        $result = $query
            ->select('id')
            ->where($query->expr()->eq('hash', ':hash'))
            ->setParameter(':hash', $hash)
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        return $result ? new AvatarId($result) : null;
    }

    /**
     * @return array
     */
    public function getAvatar(): array
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb->select('id')
            ->from(self::TABLE, 'a')
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this
            ->connection
            ->createQueryBuilder()
            ->from(self::TABLE);
    }
}
