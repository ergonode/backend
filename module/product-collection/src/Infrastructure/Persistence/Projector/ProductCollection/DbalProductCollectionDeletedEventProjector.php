<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Infrastructure\Persistence\Projector\ProductCollection;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionDeletedEvent;

class DbalProductCollectionDeletedEventProjector
{
    private const TABLE = 'product_collection';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws DBALException
     * @throws InvalidArgumentException
     */
    public function __invoke(ProductCollectionDeletedEvent $event): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
