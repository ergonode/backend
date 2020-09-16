<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Persistence\Dbal\Projector\ProductCollection;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Types\Types;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionTypeIdChangedEvent;

/**
 */
class ProductCollectionTypeIdChangedEventProjector
{
    private const TABLE = 'product_collection';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * ProductCollectionTypeIdChangedEventProjector constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param ProductCollectionTypeIdChangedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(ProductCollectionTypeIdChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'type_id' => $event->getNewTypeId()->getValue(),
                'edited_at' => $event->getEditedAt(),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ],
            [
                 'edited_at' => Types::DATETIMETZ_MUTABLE,
            ],
        );
    }
}
