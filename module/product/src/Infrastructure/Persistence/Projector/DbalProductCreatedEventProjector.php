<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
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
        $createdAt = (new \DateTime())->format(\DateTime::W3C);
        $this->connection->insert(
            self::TABLE_PRODUCT,
            [
                'id' => $event->getAggregateId()->getValue(),
                'sku' => $event->getSku()->getValue(),
                'template_id' => $event->getTemplateId()->getValue(),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
                'type' => $event->getType(),
            ]
        );
    }
}
