<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Persistence\Dbal\Projector\ProductCollectionType;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionTypeDeletedEvent;

/**
 */
class ProductCollectionTypeDeletedEventProjector
{
    private const TABLE = 'product_collection_type';

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
     * @param ProductCollectionTypeDeletedEvent $event
     *
     * @throws DBALException
     * @throws \InvalidArgumentException
     */
    public function __invoke(ProductCollectionTypeDeletedEvent $event): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
