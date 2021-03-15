<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Category\Domain\Query\TreeQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

class DbalTreeQuery implements TreeQueryInterface
{
    private const TREE_TABLE = 'category_tree';
    private const TREE_CATEGORY_TABLE = 'category_tree_category';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
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

    /**
     * @return CategoryTreeId[]
     */
    public function findCategoryTreeIdsByCategoryId(CategoryId $categoryId): array
    {
        $query = $this->connection->createQueryBuilder();
        $records = $query->select('category_tree_id')
            ->from(self::TREE_CATEGORY_TABLE)
            ->where($query->expr()->eq('category_id', ':categoryId'))
            ->setParameter(':categoryId', $categoryId->getValue())
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        $result = [];
        foreach ($records as $item) {
            $result[] = new CategoryTreeId($item);
        }

        return $result;
    }

    public function autocomplete(
        Language $language,
        string $search = null,
        int $limit = null,
        string $field = null,
        ?string $order = 'ASC'
    ): array {
        $query = $this->connection->createQueryBuilder()
            ->select('id, code, name->>:language as label')
            ->from(self::TREE_TABLE, 'c')
            ->setParameter(':language', $language->getCode());

        if ($search) {
            $query->where('code ILIKE :search');
            $query->setParameter(':search', '%'.$search.'%');
        }
        if ($field) {
            $query->orderBy($field, $order);
        }

        if ($limit) {
            $query->setMaxResults($limit);
        }

        return $query
            ->execute()
            ->fetchAll();
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('id, code')
            ->from(self::TREE_TABLE);
    }
}
