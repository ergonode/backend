<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Multimedia\Domain\Query\MultimediaQueryInterface;
use Ergonode\Multimedia\Domain\ValueObject\Hash;

/**
 */
class DbalMultimediaQuery implements MultimediaQueryInterface
{
    private const TABLE = 'multimedia';

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
