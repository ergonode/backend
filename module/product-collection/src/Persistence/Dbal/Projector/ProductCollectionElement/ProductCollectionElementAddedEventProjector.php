<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Persistence\Dbal\Projector\ProductCollectionElement;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionElementAddedEvent;

/**
 */
class ProductCollectionElementAddedEventProjector
{
    private const TABLE = 'collection_element';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param ProductCollectionElementAddedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(ProductCollectionElementAddedEvent $event): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'product_collection_id' => $event->getAggregateId(),
                'product_id' => $event->getElement()->getProductId(),
                'visible' => $event->getElement()->isVisible(),
            ]
        );
    }
}
