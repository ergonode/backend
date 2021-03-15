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
use Ergonode\ProductCollection\Domain\Query\ProductCollectionGridQueryInterface;

class DbalProductCollectionGridQuery implements ProductCollectionGridQueryInterface
{
    private const PRODUCT_COLLECTION_TABLE = 'public.product_collection';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getGridQuery(Language $language): QueryBuilder
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb
            ->select('COALESCE(t.elements_count, 0) AS elements_count')
            ->addSelect('c.id')
            ->addSelect('c.code')
            ->addSelect('c.type_id')
            ->addSelect('c.created_at')
            ->addSelect('c.edited_at')
            ->addSelect(sprintf('(c.name->>\'%s\') AS name', $language->getCode()))
            ->addSelect(sprintf('(c.description->>\'%s\') AS description', $language->getCode()))
            ->from(self::PRODUCT_COLLECTION_TABLE, 'c')
            ->leftJoin(
                'c',
                '(SELECT count(*) as elements_count, ec.product_collection_id FROM '.
                'product_collection_element ec GROUP BY ec.product_collection_id)',
                't',
                't.product_collection_id = c.id'
            );
    }
}
