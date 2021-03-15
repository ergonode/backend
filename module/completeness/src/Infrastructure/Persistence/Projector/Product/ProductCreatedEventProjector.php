<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Infrastructure\Persistence\Projector\Product;

use Ergonode\Product\Domain\Event\ProductCreatedEvent;
use Doctrine\DBAL\Connection;

class ProductCreatedEventProjector
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(ProductCreatedEvent $event): void
    {
        $this->connection->insert(
            'product_completeness',
            [
               'product_id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
