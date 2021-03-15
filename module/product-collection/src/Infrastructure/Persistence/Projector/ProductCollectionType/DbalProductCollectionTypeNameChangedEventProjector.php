<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Infrastructure\Persistence\Projector\ProductCollectionType;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionTypeNameChangedEvent;
use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;

class DbalProductCollectionTypeNameChangedEventProjector
{
    private const TABLE = 'product_collection_type';

    private Connection $connection;

    private SerializerInterface $serializer;

    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * @throws DBALException
     */
    public function __invoke(ProductCollectionTypeNameChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'name' => $this->serializer->serialize($event->getTo()->getTranslations()),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
