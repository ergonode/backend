<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Persistence\Dbal\Projector\ProductCollection;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionDeletedEvent;

/**
 */
class ProductCollectionDeletedEventProjector
{
    private const TABLE = 'collection';

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
     * @param ProductCollectionDeletedEvent $event
     *
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
