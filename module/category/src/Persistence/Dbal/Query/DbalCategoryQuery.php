<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Category\Domain\Query\CategoryQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Ergonode\Category\Domain\ValueObject\CategoryCode;

/**
 */
class DbalCategoryQuery implements CategoryQueryInterface
{
    private const CATEGORY_TABLE = 'category';

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
     * @param Language $language
     *
     * @return DataSetInterface
     */
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

    /**
     * @param CategoryCode $code
     *
     * @return CategoryId|null
     */
    public function findIdByCode(CategoryCode $code): ?CategoryId
    {
        $qb = $this->getQuery();
        $result = $qb->select('id')
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
     * @param Language $language
     *
     * @return array
     */
    public function getAll(Language $language): array
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb->select(sprintf('id, name->>\'%s\' as name, code', $language->getCode()))
            ->from(self::CATEGORY_TABLE, 'c')
            ->execute()
            ->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * @param Language $language
     *
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
     * @param CategoryId $categoryId
     *
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
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->addSelect('COALESCE(t.elements_count, 0) AS elements_count')
            ->from(self::CATEGORY_TABLE, 'c')
            ->leftJoin(
                'c',
                '(SELECT count(*) as elements_count, pcp.category_id FROM '.
                'product_category_product pcp GROUP BY pcp.category_id)',
                't',
                't.category_id = c.id'
            );
    }
}
