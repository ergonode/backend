<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Ergonode\Core\Domain\Query\LanguageTreeQueryInterface;

class DbalLanguageTreeQuery implements LanguageTreeQueryInterface
{
    private const TABLE = 'language_tree';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return array
     */
    public function getTree(): array
    {
        $qb = $this->connection->createQueryBuilder()
            ->select('child.id, child.code, child.parent_id, count(parent.id)-1 AS level')
            ->from(self::TABLE, 'child')
            ->from(self::TABLE, 'parent')
            ->where('child.lft BETWEEN parent.lft AND parent.rgt')
            ->groupBy('child.id')
            ->orderBy('child.lft');

        return $qb->execute()->fetchAll();
    }
}
