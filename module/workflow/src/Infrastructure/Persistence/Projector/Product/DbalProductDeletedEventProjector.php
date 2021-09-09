<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Persistence\Projector\Product;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Product\Domain\Event\ProductDeletedEvent;

class DbalProductDeletedEventProjector
{
    private const TABLE_WORKFLOW_PRODUCT_STATUS = 'product_workflow_status';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws DBALException
     */
    public function __invoke(ProductDeletedEvent $event): void
    {
        $productId = $event->getAggregateId()->getValue();

        $this->connection->delete(
            self::TABLE_WORKFLOW_PRODUCT_STATUS,
            [
                'product_id' => $productId,
            ]
        );
    }
}
