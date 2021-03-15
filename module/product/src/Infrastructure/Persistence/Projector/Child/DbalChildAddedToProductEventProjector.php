<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Persistence\Projector\Child;

use Doctrine\DBAL\DBALException;
use Ergonode\Product\Domain\Event\Relation\ChildAddedToProductEvent;
use Ergonode\Product\Infrastructure\Persistence\Projector\AbstractProductProjector;

class DbalChildAddedToProductEventProjector extends AbstractProductProjector
{
    private const TABLE = 'product_children';

    /**
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

        $this->updateAudit($event->getAggregateId());
    }
}
