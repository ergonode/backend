<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Persistence\Projector\Binding;

use Doctrine\DBAL\DBALException;
use Ergonode\Product\Domain\Event\Bind\BindRemovedFromProductEvent;
use Ergonode\Product\Infrastructure\Persistence\Projector\AbstractProductProjector;

class DbalBindRemovedFromProductEventProjector extends AbstractProductProjector
{
    private const TABLE = 'product_binding';

    /**
     * @throws DBALException
     */
    public function __invoke(BindRemovedFromProductEvent $event): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'product_id' => $event->getAggregateId()->getValue(),
                'attribute_id' => $event->getAttributeId()->getValue(),
            ]
        );

        $this->updateAudit($event->getAggregateId());
    }
}
