<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Query\CurrencyQueryInterface;

class DbalCurrencyQuery implements CurrencyQueryInterface
{
    private const TABLE = 'currency';
    private const FIELDS = [
        'iso AS id',
        'name AS label',
    ];

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return array
     */
    public function getDictionary(): array
    {
        return $this->getQuery()
            ->execute()
            ->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(self::FIELDS)
            ->from(self::TABLE);
    }
}
