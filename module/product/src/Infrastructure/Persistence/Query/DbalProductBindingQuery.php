<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Product\Domain\Query\ProductBindingQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class DbalProductBindingQuery implements ProductBindingQueryInterface
{
    private const ATTRIBUTE_TABLE = 'public.attribute';
    private const PRODUCT_BINDING_TABLE = 'public.product_binding';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return array
     */
    public function getBindings(ProductId $productId): array
    {
        $qb = $this->getQuery();

        return $qb
            ->select('pb.attribute_id')
            ->where($qb->expr()->eq('pb.product_id', ':id'))
            ->setParameter(':id', $productId->getValue())
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('p.id, a.code')
            ->from(self::PRODUCT_BINDING_TABLE, 'pb')
            ->join('pb', self::ATTRIBUTE_TABLE, 'a', 'a.id = pb.attribute_id');
    }
}
