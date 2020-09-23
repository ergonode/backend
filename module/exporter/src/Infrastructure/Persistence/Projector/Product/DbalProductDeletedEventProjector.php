<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\Persistence\Projector\Product;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Product\Domain\Event\ProductDeletedEvent;

/**
 */
class DbalProductDeletedEventProjector
{
    private const TABLE_PRODUCT = 'exporter.product';

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
     * @param ProductDeletedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(ProductDeletedEvent $event): void
    {
        $this->connection->delete(
            self::TABLE_PRODUCT,
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
