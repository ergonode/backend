<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Ergonode\ProductCollection\Domain\Query\ProductCollectionQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionCode;

/**
 */
class DbalProductCollectionQuery implements ProductCollectionQueryInterface
{
    private const PRODUCT_COLLECTION_TABLE = 'public.collection';

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
     * @return string[]
     */
    public function getDictionary(): array
    {
        $qb = $this->getQuery();

        return $qb
            ->select('id', 'code')
            ->execute()
            ->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * @param Language $language
     *
     * @return DataSetInterface
     */
    public function getDataSet(Language $language): DataSetInterface
    {
        $qb = $this->getQuery();
        $qb->addSelect('id');
        $qb->addSelect('code');
        $qb->addSelect('type_id');
        $qb->addSelect('created_at');
        $qb->addSelect('edited_at');
        $qb->addSelect(sprintf('(name->>\'%s\') AS name', $language->getCode()));
        $qb->addSelect(sprintf('(description->>\'%s\') AS description', $language->getCode()));

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $qb->getSQL()), 't');

        return new DbalDataSet($result);
    }

    /**
     * @param ProductCollectionCode $code
     *
     * @return ProductCollectionId|null
     */
    public function findIdByCode(ProductCollectionCode $code): ?ProductCollectionId
    {
        $qb = $this->connection->createQueryBuilder();
        $result = $qb->select('id')
            ->from(self::PRODUCT_COLLECTION_TABLE, 'c')
            ->where($qb->expr()->eq('code', ':code'))
            ->setParameter(':code', $code->getValue())
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        if ($result) {
            return new ProductCollectionId($result);
        }

        return null;
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('COALESCE(t.elements_count, 0) AS elements_count')
            ->from(self::PRODUCT_COLLECTION_TABLE, 'c')
            ->leftJoin(
                'c',
                '(SELECT count(*) as elements_count, ec.product_collection_id FROM '.
                'collection_element ec GROUP BY ec.product_collection_id)',
                't',
                't.product_collection_id = c.id'
            );
    }
}
