<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Category\Domain\Query\CategoryGridQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;

class DbalCategoryGridQuery implements CategoryGridQueryInterface
{
    private const CATEGORY_TABLE = 'category';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getDataSet(Language $language): QueryBuilder
    {
        $query = $this->connection->createQueryBuilder();

        return $query
            ->addSelect('id')
            ->addSelect('code')
            ->addSelect('sequence')
            ->addSelect(sprintf('(name->>\'%s\') AS name', $language->getCode()))
            ->addSelect('COALESCE(t.elements_count, 0) AS elements_count')
            ->from(self::CATEGORY_TABLE, 'c')
            ->leftJoin(
                'c',
                '(SELECT count(*) as elements_count, pcp.category_id FROM '.
                'product_category pcp GROUP BY pcp.category_id)',
                't',
                't.category_id = c.id'
            );
    }
}
