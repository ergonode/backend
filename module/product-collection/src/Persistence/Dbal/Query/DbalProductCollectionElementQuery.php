<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\ProductCollection\Domain\Query\ProductCollectionElementQueryInterface;

/**
 */
class DbalProductCollectionElementQuery implements ProductCollectionElementQueryInterface
{
    private const PRODUCT_COLLECTION_ELEMENT_TABLE = 'collection_element';

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
     * @param ProductCollectionId $productCollectionId
     *
     * @return DataSetInterface
     */
    public function getDataSet(ProductCollectionId $productCollectionId): DataSetInterface
    {
        $query = $this->getQuery();
        $query->andWhere($query->expr()->eq('product_collection_id', ':productCollectionId'));
        $query->addSelect('created_at');
        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $query->getSQL()), 't');
        $result->setParameter(':productCollectionId', $productCollectionId->getValue());

        return new DbalDataSet($result);
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('p.product_collection_id, p.product_id, p.visible')
            ->from(self::PRODUCT_COLLECTION_ELEMENT_TABLE, 'p');
    }
}
