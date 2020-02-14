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
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

/**
 */
class DbalProductCollectionQuery implements ProductCollectionQueryInterface
{
    private const PRODUCT_COLLECTION_TABLE = 'public.collection';
    private const PRODUCT_COLLECTION_ELEMENT_TABLE = 'public.collection_element';

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
        $query->addSelect('id');
        $query->addSelect('code');
        $query->addSelect('type_id');
        $query->addSelect('created_at');
        $query->addSelect('edited_at');
        $query->addSelect(sprintf('(name->>\'%s\') AS name', $language->getCode()));
        $query->addSelect(sprintf('(description->>\'%s\') AS description', $language->getCode()));

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $query->getSQL()), 't');

        return new DbalDataSet($result);
    }

    /**
     * @param ProductId $productId
     *
     * @return array
     */
    public function findProductCollectionIdByProduct(ProductId $productId): array
    {
        $qb = $this->connection->createQueryBuilder();

        $result = $qb->select('product_collection_id')
            ->from(self::PRODUCT_COLLECTION_ELEMENT_TABLE)
            ->where($qb->expr()->eq('product_id', ':product_id'))
            ->setParameter(':product_id', $productId->getValue())
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        if (false === $result) {
            $result = [];
        }

        foreach ($result as &$item) {
            $item = new ProductCollectionId($item);
        }

        return $result;
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
