<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Infrastructure\Manager;

use Ergonode\SharedKernel\Domain\AggregateId;
use Doctrine\DBAL\Connection;

class AggregateQuery
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     *
     * @return AggregateId[]
     */
    public function findAllAggregateOfClass(string $class): array
    {
        $result = [];

        $query = $this->connection->createQueryBuilder();
        $records = $query->select('aggregate_id')
            ->from('event_store_class')
            ->where($query->expr()->eq('class', ':class'))
            ->setParameter(':class', $class)
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        foreach ($records as $record) {
            $result[] = new AggregateId($record);
        }

        return $result;
    }
}
