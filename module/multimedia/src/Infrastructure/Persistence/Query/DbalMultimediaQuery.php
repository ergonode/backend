<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Ergonode\Multimedia\Domain\Query\MultimediaQueryInterface;
use Ergonode\Multimedia\Domain\ValueObject\Hash;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;

/**
 */
class DbalMultimediaQuery implements MultimediaQueryInterface
{
    private const TABLE = 'multimedia';

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
     * @return MultimediaId|null
     */
    public function findIdByHash(Hash $hash): ?MultimediaId
    {
        $query = $this->getQuery();
        $result = $query
            ->select('id')
            ->where($query->expr()->eq('hash', ':hash'))
            ->setParameter(':hash', $hash)
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        return $result ? new MultimediaId($result) : null;
    }

    /**
     * @param string $name
     *
     * @return MultimediaId|null
     */
    public function findIdByFilename(string $name): ?MultimediaId
    {
        $query = $this->getQuery();
        $result = $query
            ->select('id')
            ->where($query->expr()->eq('name', ':name'))
            ->setParameter(':name', $name)
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        return $result ? new MultimediaId($result) : null;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb->select('id')
            ->from(self::TABLE, 'm')
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * @return DataSetInterface
     */
    public function getDataSet(): DataSetInterface
    {
        $qb = $this->getQuery();
        $qb->select('m.id, m."name", m."extension", m.mime, m.hash, m.created_at, m.updated_at')
            ->addSelect('(left(m.mime, strpos(m.mime, \'/\')-1)) AS type')
            ->addSelect('(m.size / 1024.00)::NUMERIC(10,2) AS size')
            ->addSelect('m.id AS image')
            ->addSelect('(SELECT count(*) FROM product_value pv 
                                JOIN Value_translation vt ON vt.value_id = pv.value_id 
                                WHERE vt.value = m.id::TEXT) AS relations');

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $qb->getSQL()), 't');

        return new DbalDataSet($result);
    }

    /**
     * @return string[]
     */
    public function getTypes(): array
    {
        return $this
            ->connection
            ->createQueryBuilder()
            ->distinct()
            ->select('(left(mime, strpos(mime, \'/\')-1)) AS type')
            ->groupBy('mime')
            ->from(self::TABLE)
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
            ->from(self::TABLE, 'm');
    }
}
