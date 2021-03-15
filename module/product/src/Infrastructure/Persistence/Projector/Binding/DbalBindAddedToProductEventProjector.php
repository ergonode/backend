<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Persistence\Projector\Binding;

use Doctrine\DBAL\DBALException;
use Ergonode\Product\Domain\Event\Bind\BindAddedToProductEvent;
use Ergonode\Product\Infrastructure\Persistence\Projector\AbstractProductProjector;

class DbalBindAddedToProductEventProjector extends AbstractProductProjector
{
    private const TABLE = 'product_binding';

    /**
     * @throws DBALException
     */
    public function __invoke(BindAddedToProductEvent $event): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'product_id' => $event->getAggregateId()->getValue(),
                'attribute_id' => $event->getAttributeId()->getValue(),
            ]
        );

        $this->updateAudit($event->getAggregateId());
    }
}
