<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Infrastructure\Persistence\Projector\ProductCollectionElement;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Doctrine\DBAL\Types\Types;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionElementRemovedEvent;

class DbalProductCollectionElementRemovedEventProjector
{
    private const TABLE_ELEMENT = 'product_collection_element';
    private const TABLE_COLLECTION = 'product_collection';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws DBALException
     * @throws InvalidArgumentException
     */
    public function __invoke(ProductCollectionElementRemovedEvent $event): void
    {
        $this->connection->update(
            self::TABLE_COLLECTION,
            [
                'edited_at' => $event->getCollectionEditedAt(),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ],
            [
                'edited_at' => Types::DATETIMETZ_MUTABLE,
            ],
        );
        $this->connection->delete(
            self::TABLE_ELEMENT,
            [
                'product_collection_id' => $event->getAggregateId(),
                'product_id' => $event->getProductId(),
            ]
        );
    }
}
