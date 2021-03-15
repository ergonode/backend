<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Infrastructure\Persistence\Projector\ProductCollection;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Types\Types;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionCreatedEvent;
use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;

class DbalProductCollectionCreatedEventProjector
{
    private const TABLE = 'product_collection';

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
    public function __invoke(ProductCollectionCreatedEvent $event): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'id' => $event->getAggregateId(),
                'code' => $event->getCode(),
                'name' => $this->serializer->serialize($event->getName()->getTranslations()),
                'description' => $this->serializer->serialize($event->getDescription()->getTranslations()),
                'type_id' => $event->getTypeId(),
                'created_at' => $event->getCreatedAt(),
            ],
            [
                'created_at' => Types::DATETIMETZ_MUTABLE,
            ],
        );
    }
}
