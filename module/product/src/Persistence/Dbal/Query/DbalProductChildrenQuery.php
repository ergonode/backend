<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Query\ProductChildrenQueryInterface;

/**
 */
class DbalProductChildrenQuery implements ProductChildrenQueryInterface
{
    private const PRODUCT_TABLE = 'public.product';
    private const PRODUCT_CHILDREN_TABLE = 'public.product_children';

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
     * @param ProductId $productId
     * @param Language  $language
     *
     * @return DataSetInterface
     */
    public function getDataSet(ProductId $productId, Language $language): DataSetInterface
    {
        $qb = $this->getQuery();
        $qb->andWhere($qb->expr()->eq('product_id', ':product_id'));

        $result = $this->connection->createQueryBuilder();
        $result->setParameter(':product_id', $productId->getValue());
        $result->select('*');
        $result->from(sprintf('(%s)', $qb->getSQL()), 't');

        return new DbalDataSet($result);
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('p.id, p.sku')
            ->from(self::PRODUCT_CHILDREN_TABLE, 'pc')
            ->join('pc', self::PRODUCT_TABLE, 'p', 'p.id = pc.child_id');
    }
}
