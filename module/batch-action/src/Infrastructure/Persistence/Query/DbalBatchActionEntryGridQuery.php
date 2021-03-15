<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Ergonode\BatchAction\Domain\Query\BatchActionEntryGridQueryInterface;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\BatchAction\Domain\Entity\BatchActionId;

class DbalBatchActionEntryGridQuery implements BatchActionEntryGridQueryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getGridQuery(BatchActionId $id): QueryBuilder
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb
            ->select('be.resource_id, p.sku AS name, be.success, be.fail_reason AS messages, be.processed_at')
            ->from('batch_action_entry', 'be')
            // @todo temporary solution, this join should be moved to specific strategy base on batch action type
            ->leftJoin('be', 'product', 'p', 'p.id = be.resource_id')
            ->where($qb->expr()->eq('batch_action_id', ':qb_id'))
            ->setParameter(':qb_id', $id->getValue());
    }
}
