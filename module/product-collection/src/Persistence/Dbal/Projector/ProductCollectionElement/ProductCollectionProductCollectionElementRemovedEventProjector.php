<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Persistence\Dbal\Projector\ProductCollectionElement;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionProductCollectionElementRemovedEvent;

/**
 */
class ProductCollectionProductCollectionElementRemovedEventProjector
{
    private const TABLE = 'collection_collection_element';

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
     * @param ProductCollectionProductCollectionElementRemovedEvent $event
     *
     * @throws DBALException
     * @throws InvalidArgumentException
     */
    public function __invoke(ProductCollectionProductCollectionElementRemovedEvent $event): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'product_collection_id' => $event->getAggregateId(),
                'product_id' => $event->getProductId(),
            ]
        );
    }
}
