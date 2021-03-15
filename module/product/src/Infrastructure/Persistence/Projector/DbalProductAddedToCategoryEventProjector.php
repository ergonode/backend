<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\DBALException;
use Ergonode\Product\Domain\Event\ProductAddedToCategoryEvent;

class DbalProductAddedToCategoryEventProjector extends AbstractProductProjector
{
    private const TABLE_PRODUCT_CATEGORY = 'product_category';

    /**
     * @throws DBALException
     */
    public function __invoke(ProductAddedToCategoryEvent $event): void
    {
        $this->connection->insert(
            self::TABLE_PRODUCT_CATEGORY,
            [
                'product_id' => $event->getAggregateId()->getValue(),
                'category_id' => $event->getCategoryId()->getValue(),
            ]
        );

        $this->updateAudit($event->getAggregateId());
    }
}
