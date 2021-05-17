<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Workflow\Domain\Query\ProductWorkflowStatusQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

class DbalProductWorkflowStatusQuery implements ProductWorkflowStatusQueryInterface
{
    private const TABLE = 'product_workflow_status';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return string[]
     */
    public function getStatuses(ProductId $productId): array
    {
        $result = $this->connection->executeQuery(
            'SELECT language, status_id FROM '.self::TABLE.' WHERE product_id = :productId',
            [
                'productId' => (string) $productId,
            ],
        );

        $statuses = [];
        foreach ($result->fetchAll() as $row) {
            $statuses[$row['language']] = $row['status_id'];
        }

        return $statuses;
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
            ->from('product_workflow_status')
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
