<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\DBALException;
use Ergonode\Product\Domain\Event\ProductTemplateChangedEvent;

class DbalProductTemplateChangedEventProjector extends AbstractProductProjector
{
    private const TABLE_PRODUCT = 'product';

    /**
     * @throws DBALException
     */
    public function __invoke(ProductTemplateChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE_PRODUCT,
            [
                'template_id' => $event->getTemplateId()->getValue(),
            ],
            [
                'id' => $event->getAggregateId(),
            ]
        );

        $this->updateAudit($event->getAggregateId());
    }
}
