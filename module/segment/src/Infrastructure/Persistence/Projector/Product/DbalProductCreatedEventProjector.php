<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Persistence\Projector\Product;

use Doctrine\DBAL\Connection;
use Ergonode\Product\Domain\Event\ProductCreatedEvent;

class DbalProductCreatedEventProjector
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(ProductCreatedEvent $event): void
    {
        $this->connection->executeQuery(
            'INSERT INTO segment_product (segment_id, product_id) SELECT id, ? FROM segment',
            [$event->getAggregateId()->getValue()]
        );
    }
}
