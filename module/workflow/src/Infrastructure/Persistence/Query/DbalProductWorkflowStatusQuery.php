<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Workflow\Domain\Query\ProductWorkflowStatusQueryInterface;

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
}
