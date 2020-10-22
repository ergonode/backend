<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Category\Domain\Query\TreeQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;

class DbalTreeQuery implements TreeQueryInterface
{
    private const TREE_TABLE = 'category_tree';


    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getDataSet(Language $language): DataSetInterface
    {
        $query = $this->getQuery();
        $query->addSelect(sprintf('(name->>\'%s\') AS name', $language->getCode()));

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $query->getSQL()));

        return new DbalDataSet($query);
    }

    /**
     * @return array
     */
    public function getDictionary(Language $language): array
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb->select(sprintf('id, COALESCE(name->>\'%s\', code)', $language->getCode()))
            ->from(self::TREE_TABLE, 'c')
            ->execute()
            ->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    public function findTreeIdByCode(string $code): ?CategoryTreeId
    {
        $qb = $this->getQuery();
        $result = $qb->select('id')
            ->where($qb->expr()->eq('code', ':code'))
            ->setParameter(':code', $code)
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        if ($result) {
            return new CategoryTreeId($result);
        }

        return null;
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('id, code')
            ->from(self::TREE_TABLE);
    }
}
