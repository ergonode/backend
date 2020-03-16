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
use Ergonode\ProductCollection\Domain\Query\ProductCollectionTypeQueryInterface;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionTypeCode;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;

/**
 */
class DbalProductCollectionTypeQuery implements ProductCollectionTypeQueryInterface
{
    private const PRODUCT_COLLECTION_TYPE_TABLE = 'collection_type';
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
        $query = $this->getQuery();

        return $query
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
        $query = $this->getQuery();
        $query->addSelect(sprintf('(name->>\'%s\') AS name', $language->getCode()));

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $query->getSQL()), 't');

        return new DbalDataSet($result);
    }

    /**
     * @param ProductCollectionTypeCode $code
     *
     * @return ProductCollectionTypeId|null
     */
    public function findIdByCode(ProductCollectionTypeCode $code): ?ProductCollectionTypeId
    {
        $qb = $this->getQuery();
        $result = $qb->select('id')
            ->where($qb->expr()->eq('code', ':code'))
            ->setParameter(':code', $code->getValue())
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);

        if ($result) {
            return new ProductCollectionTypeId($result);
        }

        return null;
    }


    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('p.id, p.code')
            ->from(self::PRODUCT_COLLECTION_TYPE_TABLE, 'p');
    }
}
