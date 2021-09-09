<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\Query\ProductStatusQueryInterface;

class DbalProductStatusQuery implements ProductStatusQueryInterface
{
    private const TABLE = 'product_workflow_status';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return ProductId[]
     */
    public function findProductIdsByStatusId(StatusId $statusId): array
    {
        $qb = $this->connection->createQueryBuilder();
        $records = $qb
            ->select('product_id')
            ->distinct()
            ->from(self::TABLE)
            ->where($qb->expr()->eq('status_id', ':statusId'))
            ->setParameter(':statusId', $statusId->getValue())
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        $result = [];
        foreach ($records as $record) {
            $result[] = new ProductId($record);
        }

        return $result;
    }
}
