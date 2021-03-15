<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Domain\Query\ProductCategoryGridQueryInterface;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class DbalProductCategoryGridQuery implements ProductCategoryGridQueryInterface
{
    private const PRODUCT_CATEGORY_TABLE = 'public.product_category';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getGridQuery(Language $language, ProductId $productId): QueryBuilder
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb
            ->select('c.id, c.code, pc.product_id')
            ->addSelect(sprintf('(c.name->>\'%s\') AS name', $language->getCode()))
            ->from(self::PRODUCT_CATEGORY_TABLE, 'pc')
            ->leftJoin('pc', 'category', 'c', 'pc.category_id = c.id')
            ->where($qb->expr()->eq('product_id', ':productId'))
            ->setParameter(':productId', $productId->getValue());
    }
}
