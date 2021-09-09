<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Query\ProductRelationAttributeQueryInterface;

class DbalProductRelationAttributeQuery implements ProductRelationAttributeQueryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findProductRelatedIds(ProductId $id): array
    {
        $qb = $this->connection->createQueryBuilder();

        $records = $qb->select('DISTINCT pv.product_id')
            ->from('value_translation', 'vt')
            ->where($qb->expr()->like('value', ':id'))
            ->setParameter(':id', '%'.$id->getValue().'%')
            ->join('vt', 'product_value', 'pv', 'pv.value_id = vt.value_id')
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        $result = [];

        if ($records) {
            foreach ($records as $item) {
                $result[] = new ProductId($item);
            }
        }

        return $result;
    }
}
