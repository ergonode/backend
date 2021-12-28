<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Product\Domain\Event\ProductCreatedEvent;

class DbalProductCreatedEventProjector
{
    private const TABLE_PRODUCT = 'product';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws DBALException
     */
    public function __invoke(ProductCreatedEvent $event): void
    {
        $this->connection->insert(
            self::TABLE_PRODUCT,
            [
                'id' => $event->getAggregateId()->getValue(),
                'sku' => $event->getSku()->getValue(),
                'template_id' => $event->getTemplateId()->getValue(),
                'type' => $event->getType(),
            ]
        );
    }
}
