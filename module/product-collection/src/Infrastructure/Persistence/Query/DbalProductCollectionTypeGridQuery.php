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
use Ergonode\ProductCollection\Domain\Query\ProductCollectionTypeGridQueryInterface;

class DbalProductCollectionTypeGridQuery implements ProductCollectionTypeGridQueryInterface
{
    private const PRODUCT_COLLECTION_TYPE_TABLE = 'product_collection_type';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getGridQuery(Language $language): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('p.id, p.code')
            ->addSelect(sprintf('(name->>\'%s\') AS name', $language->getCode()))
            ->from(self::PRODUCT_COLLECTION_TYPE_TABLE, 'p');
    }
}
