<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Infrastructure\Persistence\Projector\ProductCollection;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Types\Types;
use Ergonode\ProductCollection\Domain\Event\ProductCollectionCreatedEvent;
use JMS\Serializer\SerializerInterface;

/**
 */
class DbalProductCollectionCreatedEventProjector
{
    private const TABLE = 'product_collection';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param Connection          $connection
     * @param SerializerInterface $serializer
     */
    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * @param ProductCollectionCreatedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(ProductCollectionCreatedEvent $event): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'id' => $event->getAggregateId(),
                'code' => $event->getCode(),
                'name' => $this->serializer->serialize($event->getName()->getTranslations(), 'json'),
                'description' => $this->serializer->serialize($event->getDescription()->getTranslations(), 'json'),
                'type_id' => $event->getTypeId(),
                'created_at' => $event->getCreatedAt(),
            ],
            [
                'created_at' => Types::DATETIMETZ_MUTABLE,
            ],
        );
    }
}
