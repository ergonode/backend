<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\CategoryTree\Domain\Entity\CategoryTreeId;
use Ergonode\CategoryTree\Domain\Query\TreeQueryInterface;

/**
 */
class DbalTreeQuery implements TreeQueryInterface
{
    private const TREE_TABLE = 'tree';
    private const CATEGORY_TABLE = 'category';
    private const DEPTH = 1;

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
     * @param CategoryTreeId  $id
     * @param Language        $language
     * @param CategoryId|null $nodeId
     *
     * @return array
     */
    public function getCategory(CategoryTreeId $id, Language $language, ?CategoryId $nodeId = null): array
    {
        $query = $this->getQuery();
        $query
            ->addSelect(sprintf('name->>\'%s\' AS label', $language->getCode()))
            ->andWhere($query->expr()->eq('tree_id', ':treeId'))
            ->setParameter(':treeId', $id->getValue());

        if ($nodeId) {
            $path = $this->getPath($id, $nodeId);
            $query->andWhere(sprintf('path ~ \'%s.*{%d}\'', $path, self::DEPTH));
        } else {
            $query->andWhere(sprintf('path ~ \'*{%d}\'', self::DEPTH));
        }

        return $query->execute()->fetchAll();
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('category_id AS id, nlevel(path) AS level')
            ->from(self::TREE_TABLE, 't')
            ->join('t', self::CATEGORY_TABLE, 'c', 't.category_id = c.id')
            ->orderBy('nlevel(path)', 'ASC')
            ->addOrderBy('name', 'ASC');
    }

    /**
     * @param CategoryTreeId $id
     * @param CategoryId     $nodeId
     *
     * @return null|string
     */
    private function getPath(CategoryTreeId $id, CategoryId $nodeId): ?string
    {
        $qb = $this->connection->createQueryBuilder();
        $result = $qb
            ->select('path')
            ->from(self::TREE_TABLE)
            ->where($qb->expr()->eq('tree_id', ':treeId'))
            ->andWhere($qb->expr()->eq('category_id', ':categoryId'))
            ->setParameter('treeId', $id->getValue())
            ->setParameter('categoryId', $nodeId->getValue())
            ->execute()
            ->fetchColumn();

        if ($result) {
            return $result;
        }

        return null;
    }
}
