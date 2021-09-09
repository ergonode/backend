<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Category\Domain\Query\TreeGridQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;

class DbalTreeGridQuery implements TreeGridQueryInterface
{
    private const TREE_TABLE = 'category_tree';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getDataSet(Language $language): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('id, code')
            ->addSelect('(name->>:qb_language) AS name')
            ->from(self::TREE_TABLE)
            ->setParameter(':qb_language', $language->getCode());
    }
}
