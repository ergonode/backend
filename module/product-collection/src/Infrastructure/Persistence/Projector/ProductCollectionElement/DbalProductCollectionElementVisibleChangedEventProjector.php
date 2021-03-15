<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Infrastructure\Persistence\Projector\ProductCollectionElement;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionElementVisibleChangedEvent;

class DbalProductCollectionElementVisibleChangedEventProjector
{
    private const TABLE = 'product_collection_element';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws DBALException
     */
    public function __invoke(ProductCollectionElementVisibleChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'visible' => $event->isVisible(),
            ],
            [
                'product_collection_id' => $event->getAggregateId()->getValue(),
                'product_id' => $event->getProductId()->getValue(),
            ],
            [
                'visible' => \PDO::PARAM_BOOL,
            ]
        );
    }
}
