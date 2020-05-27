<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Persistence\Dbal\Projector\Child;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Product\Domain\Event\Relation\ChildRemovedFromProductEvent;

/**
 */
class ChildRemovedFromProductEventProjector
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
     * @param ChildRemovedFromProductEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(ChildRemovedFromProductEvent $event): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'product_id' => $event->getAggregateId()->getValue(),
                'child_id' => $event->getChildId()->getValue(),
            ]
        );
    }
}
