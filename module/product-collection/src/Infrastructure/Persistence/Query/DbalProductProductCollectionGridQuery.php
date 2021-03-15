<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\ProductCollection\Domain\Query\ProductProductCollectionGridQueryInterface;

class DbalProductProductCollectionGridQuery implements ProductProductCollectionGridQueryInterface
{
    private const PRODUCT_COLLECTION_TABLE = 'public.product_collection';
    private const PRODUCT_COLLECTION_ELEMENT_TABLE = 'public.product_collection_element';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getGridQuery(Language $language, ProductId $productId): QueryBuilder
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb
            ->select('id, code, type_id')
            ->addSelect(sprintf('(name->>\'%s\') AS name', $language->getCode()))
            ->addSelect(sprintf('(description->>\'%s\') AS description', $language->getCode()))
            ->addSelect('(
                SELECT count(*) FROM product_collection_element
                WHERE product_collection_id = c.id) AS elements_count')
            ->from(self::PRODUCT_COLLECTION_TABLE, 'c')
            ->leftJoin(
                'c',
                self::PRODUCT_COLLECTION_ELEMENT_TABLE,
                'ce',
                'ce.product_collection_id = c.id'
            )
            ->where($qb->expr()->eq('product_id', ':product_id'))
            ->andWhere('visible=true')
            ->setParameter(':product_id', $productId->getValue());
    }
}
