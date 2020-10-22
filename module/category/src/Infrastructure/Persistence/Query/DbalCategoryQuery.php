<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Category\Domain\Query\CategoryQueryInterface;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

class DbalCategoryQuery implements CategoryQueryInterface
{
    private const CATEGORY_TABLE = 'category';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getDataSet(Language $language): DataSetInterface
    {
        $query = $this->getQuery();
        $query->addSelect('id');
        $query->addSelect('code');
        $query->addSelect('sequence');
        $query->addSelect(sprintf('(name->>\'%s\') AS name', $language->getCode()));

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $query->getSQL()), 't');

        return new DbalDataSet($result);
    }

    public function findIdByCode(CategoryCode $code): ?CategoryId
    {
        $qb = $this->connection->createQueryBuilder();
        $result = $qb->select('id')
            ->from(self::CATEGORY_TABLE)
            ->where($qb->expr()->eq('code', ':code'))
            ->setParameter(':code', $code->getValue())
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        if ($result) {
            return new CategoryId($result);
        }

        return null;
    }

    /**
     * @return array
     */
    public function getAll(Language $language): array
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb->select(sprintf('id, name->>\'%s\' as name, code', $language->getCode()))
            ->from(self::CATEGORY_TABLE, 'c')
            ->execute()
            ->fetchAll();
    }

    /**
     * @return array
     */
    public function getDictionary(Language $language): array
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb->select(sprintf('id, COALESCE(name->>\'%s\', code)', $language->getCode()))
            ->from(self::CATEGORY_TABLE, 'c')
            ->execute()
            ->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * @return null|array
     */
    public function getCategory(CategoryId $categoryId): ?array
    {
        $qb = $this->getQuery();
        $qb->select('id, name, code');
        $result = $qb->andWhere($qb->expr()->eq('id', ':id'))
            ->setParameter(':id', $categoryId->getValue())
            ->execute()
            ->fetch();

        if ($result) {
            $result['name'] = json_decode($result['name'], true);
            if (empty($result['name'])) {
                unset($result['name']);
            }

            return $result;
        }

        return null;
    }

    /**
     * @return array
     */
    public function autocomplete(
        Language $language,
        string $search = null,
        int $limit = null,
        string $field = null,
        ?string $order = 'ASC'
    ): array {
        $query = $this->connection->createQueryBuilder()
            ->select('id, code, COALESCE(name->>:language, null) as label')
            ->from(self::CATEGORY_TABLE, 'c')
            ->setParameter(':language', $language->getCode());

        if ($search) {
            $query->orWhere('code ILIKE :search');
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
            ->addSelect('COALESCE(t.elements_count, 0) AS elements_count')
            ->from(self::CATEGORY_TABLE, 'c')
            ->leftJoin(
                'c',
                '(SELECT count(*) as elements_count, pcp.category_id FROM '.
                'product_category pcp GROUP BY pcp.category_id)',
                't',
                't.category_id = c.id'
            );
    }
}
