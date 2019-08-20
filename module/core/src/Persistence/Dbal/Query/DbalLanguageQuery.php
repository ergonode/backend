<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;

/**
 */
class DbalLanguageQuery implements LanguageQueryInterface
{
    private const TABLE = 'language';
    private const ALL_FIELDS = [
        'id',
        'iso AS code',
        'name',
        'system',
    ];
    private const CODE_FIELD = [
        'iso AS code',
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
        $query = $this->getQuery(self::ALL_FIELDS);

        return new DbalDataSet($query);
    }

    /**
     * @param string $id
     *
     * @return array
     */
    public function getLanguage(string $id): array
    {
        $qb = $this->getQuery(self::ALL_FIELDS);

        return $qb
            ->where($qb->expr()->eq('id', ':id'))
            ->setParameter(':id', $id)
            ->execute()
            ->fetchAll();
    }

    /**
     * @return array
     */
    public function getLanguagesCodes(): array
    {
        return $this->getQuery(self::CODE_FIELD)
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * @return array
     */
    public function getSystemLanguagesCodes(): array
    {
        $qb = $this->getQuery(self::CODE_FIELD);

        return $qb
            ->where($qb->expr()->eq('system', ':system'))
            ->setParameter(':system', true, \PDO::PARAM_BOOL)
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * @param $fields
     *
     * @return QueryBuilder
     */
    private function getQuery($fields): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select($fields)
            ->from(self::TABLE);
    }
}
