<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Persistence\Dbal\Projector\ProductCollectionElement;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionElementVisibleChangedEvent;

/**
 */
class ProductCollectionElementVisibleChangedEventProjector
{
    private const TABLE = 'product_collection_element';

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
     * @param ProductCollectionElementVisibleChangedEvent $event
     *
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
