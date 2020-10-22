<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Persistence\Projector\Child;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Product\Domain\Event\Relation\ChildAddedToProductEvent;

class DbalChildAddedToProductEventProjector
{
    private const TABLE = 'product_children';

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
     * @param ChildAddedToProductEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(ChildAddedToProductEvent $event): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'product_id' => $event->getAggregateId()->getValue(),
                'child_id' => $event->getChildId()->getValue(),
            ]
        );
    }
}
