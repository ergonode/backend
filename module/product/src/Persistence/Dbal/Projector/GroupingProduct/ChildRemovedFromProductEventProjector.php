<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Persistence\Dbal\Projector\GroupingProduct;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Product\Domain\Event\GroupingProduct\ChildRemovedFromProductEvent;

/**
 */
class ChildRemovedFromProductEventProjector
{
    private const TABLE_PRODUCT_CATEGORY = 'product_children';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param ChildRemovedFromProductEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(ChildRemovedFromProductEvent $event): void
    {
        $this->connection->delete(
            self::TABLE_PRODUCT_CATEGORY,
            [
                'product_id' => $event->getAggregateId()->getValue(),
                'child_id' => $event->getChildId()->getValue(),
            ]
        );
    }
}
